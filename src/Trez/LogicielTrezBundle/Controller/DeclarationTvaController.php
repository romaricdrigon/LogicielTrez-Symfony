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
        // TODO : besoin de se placer dans un exercice également

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list.html.twig');
    }

    // create will just set a declarationTva date, then redirect to listFactures
    public function createAction()
    {
        $object = new DeclarationTva();
        $form = $this->get('form.factory')->create(new DeclarationTvaDate(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                // TODO : set day as 1-MM-YY

                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', 'La déclaration de TVA a été créée !');

                return new RedirectResponse($this->generateUrl('declaration_tva_list_factures', array('id'=> $object->getId())));
            }
        }

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:create.html.twig', array(
                'form' => $form->createView()
            ));
    }

    // list all factures available for this declarationTva
    public function listFacturesAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);

        $date_time = $declaration->getDate();

        $date_next_month = clone $date_time; // watch, out, add will modify the object
        $date_next_month = $date_next_month->add(new \DateInterval('P1M'));

        // TODO : select only undeclared factures
        // watch out when editing a declaration
        $form = $this->get('form.factory')->create(
            new DeclarationTvaFactures($date_time, $date_next_month),
            $declaration
        );

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                // we have to persist each facture
                foreach ($declaration->getFactures() as $facture) {
                    $facture->setDeclarationTva($declaration);
                    $em->persist($facture);
                }

                // TODO : select solde_precedent

                $em->persist($declaration);
                $em->flush();

                return new RedirectResponse($this->get('router')->generate('declaration_tva_edit', array('id'=> $declaration->getId())));
            }
        }

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list_factures.html.twig', array(
                'form' => $form->createView(),
                'declaration_tva' => $declaration
            ));
    }

    // edit a declarationTva (quite limited)
    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);
        $form = $this->get('form.factory')->create(new DeclarationTvaEdit(), $declaration);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $em->persist($declaration);
                $em->flush();

                $this->get('session')->setFlash('success', 'La déclaration a bien été éditée');
            }
        }

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:edit.html.twig', array(
                'form' => $form->createView(),
                'declaration_tva' => $declaration
            ));
    }

    public function generateSheetAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);
        $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(
            array('declarationTva' => $declaration)
        );
        //Création du tableau javascript qui va être affiché
        $data="";
        $i = 0;
        foreach ($factures as $facture)
        {
            $data .= '
                data['.$i.'] = {
                numero: "'.$facture->getId().'",
                objet: "'.$facture->getObjet().'",
                montant: "'.$facture->getMontant().'",
                date: "'.$facture->getDatePaiement()->format('d-m-Y').'"
                };
            ';
            $i++;
        }

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:generateSheet.html.twig', array(
                'declaration' => $declaration,
                'factures' => $factures,
                'data' => $data,
                'nombreLigne' => $i
            ));
    }
}
