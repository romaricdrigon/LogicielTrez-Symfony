<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConfigController extends Controller
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
        $configs = $em->getRepository('TrezLogicielTrezBundle:Config')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('configs' => $configs));
    }
}
