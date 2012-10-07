<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BudgetController extends Controller
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
        $budgets = $em->getRepository('TrezLogicielTrezBundle:Budget')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('budgets' => $budgets));
    }
}
