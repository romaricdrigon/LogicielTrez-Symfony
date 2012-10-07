<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LigneController extends Controller
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
        $lignes = $em->getRepository('TrezLogicielTrezBundle:Ligne')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('lignes' => $lignes));
    }
}
