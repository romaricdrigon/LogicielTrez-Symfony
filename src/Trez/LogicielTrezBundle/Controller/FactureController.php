<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Facture;
use Trez\LogicielTrezBundle\Form\FactureType;

class FactureController extends Controller
{
    public function indexAction($ligne_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);
        $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(
            ['ligne' => $ligne],
            ['numero' => 'DESC']);

        $this->getBreadcrumbs($ligne);

        $ligne->getFreeTotal($c, $d);

        return $this->render('TrezLogicielTrezBundle:Facture:list.html.twig', [
            'factures' => $factures,
            'ligne' => $ligne,
            'is_full' => ($c === 0.0) && ($d === 0.0)
        ]);
    }

    public function detailAction($ligne_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);
        $tvas = $em->getRepository('TrezLogicielTrezBundle:Tva')->findBy(
            ['facture' => $object]
        );

        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);
        $this->getBreadcrumbs($ligne);

        return $this->render('TrezLogicielTrezBundle:Facture:detail.html.twig', array(
            'facture' => $object,
            'ligne_id' => $ligne_id,
            'tvas' => $tvas
        ));
    }

    public function addAction($ligne_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);

        $object = new Facture();
        $object->setLigne($ligne);
        $form = $this->get('form.factory')->create(new FactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                // TODO : if user asked to adjust the line, we now take care of the stuff

                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La facture a bien été émise");

                return new RedirectResponse($this->generateUrl('facture_index', ['ligne_id' => $ligne_id]));
            }
        }

        $this->getBreadcrumbs($ligne);

        return $this->render('TrezLogicielTrezBundle:Facture:add.html.twig', array(
            'form' => $form->createView(),
            'ligne_id' => $ligne_id
        ));
    }

    public function editAction($ligne_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);
        $form = $this->get('form.factory')->create(new FactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('facture_index', ['ligne_id' => $ligne_id]));
            }
        }

        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);
        $this->getBreadcrumbs($ligne);

        return $this->render('TrezLogicielTrezBundle:Facture:edit.html.twig', array(
            'form' => $form->createView(),
            'facture' => $object,
            'ligne_id' => $ligne_id
        ));
    }

    public function deleteAction($ligne_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Facture supprimée !');

        return new RedirectResponse($this->generateUrl('facture_index', ['ligne_id' => $ligne_id]));
    }

    private function getBreadcrumbs($ligne)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercice ".$ligne->getSousCategorie()->getCategorie()->getBudget()->getExercice()->getEdition(), $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Budget ".$ligne->getSousCategorie()->getCategorie()->getBudget()->getNom(), $this->generateUrl('budget_index', ['exercice_id' => $ligne->getSousCategorie()->getCategorie()->getBudget()->getExercice()->getId()]));
        $breadcrumbs->addItem("Catégorie  ".$ligne->getSousCategorie()->getCategorie()->getNom(), $this->generateUrl('categorie_index', ['budget_id' => $ligne->getSousCategorie()->getCategorie()->getBudget()->getId()]));
        $breadcrumbs->addItem("Sous-catégorie  ".$ligne->getSousCategorie()->getNom(), $this->generateUrl('sous_categorie_index', ['categorie_id' => $ligne->getSousCategorie()->getCategorie()->getBudget()->getId()]));
        $breadcrumbs->addItem("Ligne ".$ligne->getnom(), $this->generateUrl('ligne_index', ['sous_categorie_id' => $ligne->getSousCategorie()->getId()]));
        $breadcrumbs->addItem("Facture", $this->generateUrl('facture_index', ['ligne_id' => $ligne->getId()]));
    }
}