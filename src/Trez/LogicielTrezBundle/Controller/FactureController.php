<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FactureController extends Controller
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
        $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('factures' => $factures));
    }
}
