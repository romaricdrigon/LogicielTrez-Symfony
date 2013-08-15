<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $categories = $aclFactory->getBudget($budget)->getCategories();

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($budget);

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
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->getFlashBag()->set('success', "La catégorie a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('categorie_index', array('budget_id' => $budget_id)));
            }
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($budget, 'Ajouter une catégorie');

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
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->getFlashBag()->set('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('categorie_index', array('budget_id' => $budget_id)));
            }
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($object, 'Modifier la catégorie');

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

        $this->get('session')->getFlashBag()->set('info', 'Catégorie supprimée !');

        return new RedirectResponse($this->generateUrl('categorie_index', array('budget_id' => $budget_id)));
    }
}
