<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
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
            $date_time = $form['mois']->getData();

            var_dump($date_time);

            $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->getFacturesByMonth(1, $date_time);

            return $this->render('TrezLogicielTrezBundle:DeclarationTva:list_factures.html.twig', array(
                    'factures' => $factures,
                    'mois' => $date_time->format('m/y')
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
            ->add('mois', 'date', array(
                    'input' => 'datetime',
                    'widget' => 'choice'
                ))
            ->getForm();
    }
}
