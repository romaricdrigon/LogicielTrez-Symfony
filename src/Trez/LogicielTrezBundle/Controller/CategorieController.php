<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Trez\LogicielTrezBundle\Entity\Categorie;
use Trez\LogicielTrezBundle\Form\CategorieType;

class CategorieController extends Controller
{
    public function indexAction($budget_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $budget = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($budget_id);

        // list only categories user can read
        $aclFactory = $this->get('trez.logiciel_trez.acl_proxy_factory');
        $categories = $aclFactory->get('Budget', $budget)->getCategories();

        if ($categories === array()) {
            throw new AccessDeniedException();
        }

        $this->getBreadcrumbs($budget);

        return $this->render('TrezLogicielTrezBundle:Categorie:list.html.twig', array(
            'categories' => $categories,
            'budget' => $budget
        ));
    }

    public function addAction($budget_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $budget = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($budget_id);
        $cle = $em->getRepository('TrezLogicielTrezBundle:Categorie')->getLastCle($budget_id);

        $object = new Categorie();
        $object->setBudget($budget);
        $object->setCle($cle[0]['cle']+1);

        $form = $this->get('form.factory')->create(new CategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La catégorie a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('categorie_index', array('budget_id' => $budget_id)));
            }
        }

        $this->getBreadcrumbs($budget);

        return $this->render('TrezLogicielTrezBundle:Categorie:add.html.twig', array(
            'form' => $form->createView(),
            'budget_id' => $budget_id
        ));
    }

    public function editAction($budget_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($id);
        $form = $this->get('form.factory')->create(new CategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('categorie_index', array('budget_id' => $budget_id)));
            }
        }

        $budget = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($budget_id);
        $this->getBreadcrumbs($budget);

        return $this->render('TrezLogicielTrezBundle:Categorie:edit.html.twig', array(
            'form' => $form->createView(),
            'categorie' => $object,
            'budget_id' => $budget_id
        ));
    }

    public function deleteAction($budget_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Catégorie supprimée !');

        return new RedirectResponse($this->generateUrl('categorie_index', array('budget_id' => $budget_id)));
    }

    private function getBreadcrumbs($budget)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercices", $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Budgets de ".$budget->getExercice()->getEdition(), $this->generateUrl('budget_index', array('exercice_id' => $budget->getExercice()->getId())));
        $breadcrumbs->addItem("Catégories de ".$budget->getNom(), $this->generateUrl('categorie_index', array('budget_id' => $budget->getId())));
    }
}
