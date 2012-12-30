<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Trez\LogicielTrezBundle\Entity\SousCategorie;
use Trez\LogicielTrezBundle\Form\SousCategorieType;

class SousCategorieController extends Controller
{
    public function indexAction($categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $categorie = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($categorie_id);
        $sousCategories = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->getAll($categorie_id);

        // check if user is ok
        $sc = $this->get('security.context');
        if ($sc->isGranted('ROLE_ADMIN') === false && $sc->isGranted('ROLE_USER') === true) {
            if (method_exists($sc->getToken()->getUser(), 'isCategorieAllowed') === true
                && $sc->getToken()->getUser()->isCategorieAllowed($categorie) === true) {
                // inversed if, otherwise a bit hard to read!
            } else {
                throw new AccessDeniedException();
            }
        }


        $this->getBreadcrumbs($categorie);

        return $this->render('TrezLogicielTrezBundle:SousCategorie:list.html.twig', array(
            'sous_categories' => $sousCategories,
            'categorie' => $categorie
        ));
    }

    public function addAction($categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $categorie = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($categorie_id);
        $cle = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->getLastCle($categorie_id);

        $object = new SousCategorie();
        $object->setCategorie($categorie);
        $object->setCle($cle[0]['cle']+1);

        $form = $this->get('form.factory')->create(new SousCategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La sous-catégorie a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('sous_categorie_index', array('categorie_id' => $categorie_id)));
            }
        }

        $this->getBreadcrumbs($categorie);

        return $this->render('TrezLogicielTrezBundle:SousCategorie:add.html.twig', array(
            'form' => $form->createView(),
            'categorie_id' => $categorie_id
        ));
    }

    public function editAction($categorie_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($id);
        $form = $this->get('form.factory')->create(new SousCategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('sous_categorie_index', array('categorie_id' => $categorie_id)));
            }
        }

        $categorie = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($categorie_id);
        $this->getBreadcrumbs($categorie);

        return $this->render('TrezLogicielTrezBundle:SousCategorie:edit.html.twig', array(
            'form' => $form->createView(),
            'sous_categorie' => $object,
            'categorie_id' => $categorie_id
        ));
    }

    public function deleteAction($categorie_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Sous-catégorie supprimée !');

        return new RedirectResponse($this->generateUrl('categorie_index', array('categorie_id' => $categorie_id)));
    }

    private function getBreadcrumbs($categorie)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercices", $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Budgets de ".$categorie->getBudget()->getExercice()->getEdition(), $this->generateUrl('budget_index', array('exercice_id' => $categorie->getBudget()->getExercice()->getId())));
        $breadcrumbs->addItem("Catégories de ".$categorie->getBudget()->getNom(), $this->generateUrl('categorie_index', array('budget_id' => $categorie->getBudget()->getId())));
        $breadcrumbs->addItem("Sous-catégories de  ".$categorie->getNom(), $this->generateUrl('sous_categorie_index', array('categorie_id' => $categorie->getId())));
    }
}

