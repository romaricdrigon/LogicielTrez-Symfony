<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TiersController extends Controller
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
        $tiers = $em->getRepository('TrezLogicielTrezBundle:Tiers')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('tiers' => $tiers));
    }
}
