<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Trez\LogicielTrezBundle\Entity\Categorie;
use Trez\LogicielTrezBundle\Form\CategorieType;

class CategorieController extends Controller
{
    /*
     * (detail)
     * add
     * edit(budget_id, id)
     * delete(budget_id, id)
     */

    public function indexAction($budget_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $budget = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($budget_id);
        $categories = $em->getRepository('TrezLogicielTrezBundle:Categorie')->findOneByBudget($budget);

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('categories' => $categories));
    }

    public function addAction($budget_id)
    {
        $object = new Categorie();
        $form = $this->get('form.factory')->create(new CategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La catégorie a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('categorie_index', ['budget_id' => $budget_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Categorie:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($budget_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($id);
        $form = $this->get('form.factory')->create(new \Trez\LogicielTrezBundle\Form\CategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('categorie_index', ['budget_id' => $budget_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Categorie:edit.html.twig', array(
            'form' => $form->createView(),
            'categorie' => $object
        ));
    }

    public function deleteAction($budget_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Catégorie supprimée !');

        return new RedirectResponse($this->generateUrl('categorie_index', ['budget_id' => $budget_id]));
    }
}
