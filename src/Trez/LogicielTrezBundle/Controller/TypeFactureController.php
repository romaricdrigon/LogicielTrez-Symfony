<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TypeFactureController extends Controller
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
        $typeFactures = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('typeFactures' => $typeFactures));
    }
}
