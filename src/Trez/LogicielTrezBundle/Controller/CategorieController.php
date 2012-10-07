<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategorieController extends Controller
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
        $categories = $em->getRepository('TrezLogicielTrezBundle:Categorie')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('categories' => $categories));
    }
}
