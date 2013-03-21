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

    public function generateSheetAction()
    {
        return new Response();
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
