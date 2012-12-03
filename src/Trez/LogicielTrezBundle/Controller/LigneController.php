<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Ligne;
use Trez\LogicielTrezBundle\Form\LigneType;

class LigneController extends Controller
{
    public function indexAction($sous_categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sous_categorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sous_categorie_id);
        $lignes = $em->getRepository('TrezLogicielTrezBundle:Ligne')->findBy(
            ['sousCategorie' => $sous_categorie_id],
            ['cle' => 'ASC']
        );

        $this->getBreadcrumbs($sous_categorie);

        return $this->render('TrezLogicielTrezBundle:Ligne:list.html.twig', [
            'lignes' => $lignes,
            'sous_categorie' => $sous_categorie
        ]);
    }

    public function addAction($sous_categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sous_categorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sous_categorie_id);

        $object = new Ligne();
        $object->setSousCategorie($sous_categorie);

        $form = $this->get('form.factory')->create(new LigneType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La ligne a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('ligne_index', ['sousCategorie_id' => $sous_categorie_id]));
            }
        }

        $this->getBreadcrumbs($sous_categorie);

        return $this->render('TrezLogicielTrezBundle:Ligne:add.html.twig', array(
            'form' => $form->createView(),
            'sous_categorie_id' => $sous_categorie_id
        ));
    }

    public function editAction($sous_categorie_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $form = $this->get('form.factory')->create(new LigneType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('ligne_index', ['sous_categorie_id' => $sous_categorie_id]));
            }
        }

        $sous_categorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sous_categorie_id);
        $this->getBreadcrumbs($sous_categorie);

        return $this->render('TrezLogicielTrezBundle:Ligne:edit.html.twig', array(
            'form' => $form->createView(),
            'ligne' => $object,
            'sous_categorie_id' => $sous_categorie_id
        ));
    }

    public function deleteAction($sous_categorie_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Ligne supprimée !');

        return new RedirectResponse($this->generateUrl('ligne_index', ['sous_categorie_id' => $sous_categorie_id]));
    }

    private function getBreadcrumbs($sous_categorie)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercices", $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Budgets de ".$sous_categorie->getCategorie()->getBudget()->getExercice()->getEdition(), $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Catégories de ".$sous_categorie->getCategorie()->getBudget()->getNom(), $this->generateUrl('categorie_index', ['budget_id' => $sous_categorie->getCategorie()->getBudget()->getId()]));
        $breadcrumbs->addItem("Sous-catégories de  ".$sous_categorie->getCategorie()->getNom(), $this->generateUrl('sous_categorie_index', ['categorie_id' => $sous_categorie->getCategorie()->getBudget()->getId()]));
        $breadcrumbs->addItem("Lignes de  ".$sous_categorie->getNom(), $this->generateUrl('ligne_index', ['sous_categorie_id' => $sous_categorie->getId()]));
    }
}