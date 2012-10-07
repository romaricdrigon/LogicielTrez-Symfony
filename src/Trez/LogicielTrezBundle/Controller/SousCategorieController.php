<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SousCategorieController extends Controller
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
        $sousCategories = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('sousCategories' => $sousCategories));
    }
}
