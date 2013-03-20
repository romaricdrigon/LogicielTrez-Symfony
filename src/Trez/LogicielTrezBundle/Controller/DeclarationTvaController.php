<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class DeclarationTvaController extends Controller
{
    public function indexAction()
    {
        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list.html.twig', array(
                'form' => $this->getDateForm()->createView()
            ));
    }

    public function listFacturesAction()
    {
        $request = $this->get('request');
        $form = $this->getDateForm();
        $form->bind($request);

        if ($form->isValid()) {
            return new Response(print_r($form->getData(), false));
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
            ->add('mois', 'date')
            ->getForm();
    }
}
