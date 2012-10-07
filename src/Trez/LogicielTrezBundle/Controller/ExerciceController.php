<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ExerciceController extends Controller
{
    /*
     * (detail)
     * add
     * edit(id)
     * delete(id)
     */

    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $exercices = $em->getRepository('TrezLogicielTrezBundle:Exercice')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('exercices' => $exercices));
    }
}
