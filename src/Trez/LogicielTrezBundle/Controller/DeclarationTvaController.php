<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\Request;

class DeclarationTvaController extends Controller
{
    public function indexAction()
    {
        // TODO : besoin de se placer dans un exercice également
        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list.html.twig', array(
                'form' => $this->getDateForm()->createView()
            ));
    }

    public function listFacturesAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->get('request');
        $form = $this->getDateForm();
        $form->bind($request);

        if ($form->isValid()) {
            $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->getFacturesByMonth(1, $form['mois']->getData());

            return $this->render('TrezLogicielTrezBundle:DeclarationTva:list_factures.html.twig', array(
                    'factures' => $factures,
                    'mois' => $form['mois']->getData()
                ));
        } else {
            throw new \Exception('La date fournie semble invalide');
        }
    }

    public function createAction()
    {
        return new Response();
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
        $dataRecu="";
        $dataRendu="";
        $dataTva="";
        $i=0;
        foreach($tvaRecu as $tva)
        {
            $dataRecu .= '
                dataRecu['.$i.'] = {
                numero: "'.$tva->getFacture()->getTypeFacture()->getAbr().' '.$tva->getFacture()->getId().'",
                objet: "'.$tva->getFacture()->getObjet().'",
                montant: "'.$tva->getFacture()->getMontant().' '.$currency[0]->getValeur().'",
                tva: "'.$tva->getMontantTva().'"
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
        $i=0;
        foreach($tvaRendu as $tva)
        {
            $dataRendu .= '
                dataRendu['.$i.'] = {
                numero: "'.$tva->getFacture()->getTypeFacture()->getAbr().' '.$tva->getFacture()->getId().'",
                objet: "'.$tva->getFacture()->getObjet().'",
                montant: "'.$tva->getFacture()->getMontant().' '.$currency[0]->getValeur().'",
                tva: "'.$tva->getMontantTva().'"
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

        $sommeHTRecu = $htRecu196 + $htRecu7 + $htRecu5 + $htRecuAutre;
        $sommeHTRendu = $htRendu196 + $htRendu7 + $htRendu5 + $htRenduAutre;

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:generateSheet.html.twig', array(
            'declaration' => $declaration,
            'dataRecu' => $dataRecu,
            'dataRendu' => $dataRendu,
            'dataTva' => $dataTva,
            'sommeHTRecu' => $sommeHTRecu,
            'sommeHTRendu' => $sommeHTRendu,
            'tvaRecu196' => $tvaRecu196,
            'tvaRecu7' => $tvaRecu7,
            'tvaRecu5' => $tvaRecu5,
            'tvaRecuAutre' => $tvaRecuAutre,
            'htRecu196' => $htRecu196,
            'htRecu7'=> $htRecu7,
            'htRecu5' => $htRecu5,
            'htRecuAutre' => $htRecuAutre,
            'tvaRendu196' => $tvaRendu196,
            'tvaRendu7' => $tvaRendu7,
            'tvaRendu5' => $tvaRendu5,
            'tvaRenduAutre' => $tvaRenduAutre,
            'htRendu196' => $htRendu196,
            'htRendu7'=> $htRendu7,
            'htRendu5' => $htRendu5,
            'htRenduAutre' => $htRenduAutre,
        ));
    }

    /* for index and listFactures */
    private function getDateForm()
    {
        // by default, previous month
        $defaultData = array('mois' => new \DateTime(date('Y-m-1', strtotime('last month'))));

        return $this->createFormBuilder($defaultData)
            ->add('exercice', 'entity', array(
                    'class' => 'Trez\LogicielTrezBundle\Entity\Exercice',
                    'property' => 'edition'
                ))
            ->add('mois', 'date')
            ->getForm();
    }
}
