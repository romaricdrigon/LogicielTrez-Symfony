<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClasseTvaController extends Controller
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
        $classeTvas = $em->getRepository('TrezLogicielTrezBundle:ClasseTva')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('classeTvas' => $classeTvas));
    }
}
