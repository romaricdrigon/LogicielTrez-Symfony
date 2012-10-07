<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MethodePaiementController extends Controller
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
        $methodePaiements = $em->getRepository('TrezLogicielTrezBundle:MethodePaiement')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('methodePaiements' => $methodePaiements));
    }
}
