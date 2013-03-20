<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DeclarationTvaController extends Controller
{
    public function indexAction()
    {
        $defaultData = array('message' => 'Je ne sais pas à quoi cela sert');
        $form = $this->createFormBuilder($defaultData)
            ->add('mois', 'date')
            ->getForm();

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list.html.twig', array(
                'form' => $form
            ));
    }

    public function listFacturesAction($date)
    {
        if ($date == -1) {

        }

        return new Response();
    }

    public function createAction()
    {
        return new Response();
    }

    public function generateSheetAction()
    {
        return new Response();
    }
}
