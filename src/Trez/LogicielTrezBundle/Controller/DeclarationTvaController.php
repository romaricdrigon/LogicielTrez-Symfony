<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Trez\LogicielTrezBundle\Entity\DeclarationTva;
use Trez\LogicielTrezBundle\Form\DeclarationTvaDate;
use Trez\LogicielTrezBundle\Form\DeclarationTvaEdit;
use Trez\LogicielTrezBundle\Form\DeclarationTvaFactures;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeclarationTvaController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declarations = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->findAll(
            array('date', 'DESC')
        );

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list.html.twig', array(
                'declarations' => $declarations
            ));
    }

    // create will just set a declarationTva date, then redirect to listFactures
    public function createAction()
    {
        $object = new DeclarationTva();
        $form = $this->get('form.factory')->create(new DeclarationTvaDate(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                // force date to first day of month
                $date = $object->getDate();
                $newDate = \DateTime::createFromFormat('Y-m-d', $date->format('Y-m') . '-1', $date->getTimezone());
                $object->setDate($newDate);

                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', 'La déclaration de TVA a été créée !');

                return new RedirectResponse($this->generateUrl(
                    'declaration_tva_list_factures',
                    array('id' => $object->getId())
                ));
            }
        }

        return $this->render(
            'TrezLogicielTrezBundle:DeclarationTva:create.html.twig',
            array(
                'form' => $form->createView()
            )
        );
    }

    // list all factures available for this declarationTva
    public function listFacturesAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);

        $date_time = $declaration->getDate();

        $date_next_month = clone $date_time; // watch, out, add will modify the object
        $date_next_month = $date_next_month->add(new \DateInterval('P1M'));

        $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(
            array('declarationTva' => null),
            array('date' => 'ASC')
        );

        // watch out when editing a declaration
        $form = $this->get('form.factory')->create(new DeclarationTvaFactures(), $declaration);

        $request = $this->get('request');
        if ('POST' === $request->getMethod() && $form->bind($request)->isValid()) {
            // we have to persist each facture
            foreach ($declaration->getFactures() as $facture) {
                $facture->setDeclarationTva($declaration);
                $em->persist($facture);
            }

            // TODO : select solde_precedent

            $em->persist($declaration);
            $em->flush();

            return new RedirectResponse(
                $this->get('router')->generate(
                    'declaration_tva_edit',
                    array('id' => $declaration->getId())
                )
            );
        }

        return $this->render(
            'TrezLogicielTrezBundle:DeclarationTva:list_factures.html.twig',
            array(
                'form'            => $form->createView(),
                'declaration_tva' => $declaration,
                'factures'        => $factures,
                'date_debut'      => $date_time,
                'date_fin'        => $date_next_month
            )
        );
    }

    // edit a declarationTva (quite limited)
    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);
        $form = $this->get('form.factory')->create(new DeclarationTvaEdit(), $declaration);

        $request = $this->get('request');
        if ('POST' === $request->getMethod() && $form->bind($request)->isValid()) {
            $em->persist($declaration);
            $em->flush();

            $this->get('session')->setFlash('success', 'La déclaration a bien été éditée');
        }

        return $this->render(
            'TrezLogicielTrezBundle:DeclarationTva:edit.html.twig',
            array(
                'form'            => $form->createView(),
                'declaration_tva' => $declaration
            )
        );
    }

    public function generateSheetAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $currency = $em->getRepository('TrezLogicielTrezBundle:Config')->findBy(
            array('cle' => 'currency')
        );
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);

        //Récupération des factures dont on récupère la tva, puis celle dont on la rend.
        $tvaRecu = $em->getRepository('TrezLogicielTrezBundle:Tva')->getFactures($id, 0);
        $tvaRendu = $em->getRepository('TrezLogicielTrezBundle:Tva')->getFactures($id, 1);

        //Pour chaque tva, on stocke le total ainsi que le montant ht
        $tvaRecu196 = 0;
        $tvaRecu7 = 0;
        $tvaRecu5 = 0;
        $tvaRecuAutre = 0;
        $htRecu196 = 0;
        $htRecu7 = 0;
        $htRecu5 = 0;
        $htRecuAutre = 0;

        //La même chose pour la tva que l'on rend
        $tvaRendu196 = 0;
        $tvaRendu7 = 0;
        $tvaRendu5 = 0;
        $tvaRenduAutre = 0;
        $htRendu196 = 0;
        $htRendu7 = 0;
        $htRendu5 = 0;
        $htRenduAutre = 0;

        //Création du tableau javascript qui va être affiché pour les factures émises, sens = 1
        $dataRecu = "";
        $dataRendu = "";
        $dataTva = "";
        $i = 0;
        foreach ($tvaRecu as $tva) {
            $dataRecu .= '
                dataRecu[' . $i . '] = {
                numero: "' . $tva->getFacture()->getTypeFacture()->getAbr() . ' ' . $tva->getFacture()->getId() . '",
                objet: "' . $tva->getFacture()->getObjet() . '",
                montant: "' . $tva->getFacture()->getMontant() . ' ' . $currency[0]->getValeur() . '",
                tva: "' . $tva->getMontantTva() . '"
                };
            ';
            switch ($tva->getClasseTva()->getTaux()) {
                case 19.6:
                    $tvaRecu196 += $tva->getMontantTva();
                    $htRecu196 += $tva->getFacture()->getMontant();
                    break;
                case 7:
                    $tvaRecu7 += $tva->getMontantTva();
                    $htRecu7 += $tva->getFacture()->getMontant();
                    break;
                case 5:
                    $tvaRecu5 += $tva->getMontantTva();
                    $htRecu5 += $tva->getFacture()->getMontant();
                    break;
                default:
                    $htRecuAutre += $tva->getMontantTva();
                    $tvaRecuAutre += $tva->getFacture()->getMontant();
                    break;
            }
            $i++;
        }
        $i = 0;
        foreach ($tvaRendu as $tva) {
            $dataRendu .= '
                dataRendu[' . $i . '] = {
                numero: "' . $tva->getFacture()->getTypeFacture()->getAbr() . ' ' . $tva->getFacture()->getId() . '",
                objet: "' . $tva->getFacture()->getObjet() . '",
                montant: "' . $tva->getFacture()->getMontant() . ' ' . $currency[0]->getValeur() . '",
                tva: "' . $tva->getMontantTva() . '"
                };
            ';
            switch ($tva->getClasseTva()->getTaux()) {
                case 19.6:
                    $tvaRendu196 += $tva->getMontantTva();
                    $htRendu196 += $tva->getFacture()->getMontant();
                    break;
                case 7:
                    $tvaRendu7 += $tva->getMontantTva();
                    $htRendu7 += $tva->getFacture()->getMontant();
                    break;
                case 5:
                    $tvaRendu5 += $tva->getMontantTva();
                    $htRendu5 += $tva->getFacture()->getMontant();
                    break;
                default:
                    $htRenduAutre += $tva->getMontantTva();
                    $tvaRenduAutre += $tva->getFacture()->getMontant();
                    break;
            }
            $i++;
        }

        return $this->render(
            'TrezLogicielTrezBundle:DeclarationTva:generateSheet.html.twig',
            array(
                'declaration'   => $declaration,
                'dataRecu'      => $dataRecu,
                'dataRendu'     => $dataRendu,
                'dataTva'       => $dataTva,
                'tvaRendu196'   => $tvaRendu196,
                'tvaRendu7'     => $tvaRendu7,
                'tvaRendu5'     => $tvaRendu5,
                'tvaRenduAutre' => $tvaRenduAutre,
                'htRendu196'    => $htRendu196,
                'htRendu7'      => $htRendu7,
                'htRendu5'      => $htRendu5,
                'htRenduAutre'  => $htRenduAutre,
                'tvaRendu'      => $tvaRendu196 + $tvaRendu7 + $tvaRendu5,
                'tvaRecu'       => $tvaRecu196 + $tvaRecu7 + $tvaRecu5 + $tvaRecuAutre,

            )
        );

    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Déclaration supprimée !');

        return new RedirectResponse($this->generateUrl('declaration_tva_index'));
    }
}
