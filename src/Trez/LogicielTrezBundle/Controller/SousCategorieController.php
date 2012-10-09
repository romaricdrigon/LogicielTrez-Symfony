<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\SousCategorie;
use Trez\LogicielTrezBundle\Form\SousCategorieType;

class SousCategorieController extends Controller
{

    /*
         * (detail)
         * add
         * edit(categorie_id, id)
         * delete(categorie_id, id)
         */

    public function indexAction($categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $categorie = $em->getRepository('TrezLogicielTrezBundle:Categorie')->find($categorie_id);
        $sousCategories = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->findOneByCategorie($categorie);

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('sousCategories' => $sousCategories));
    }

    public function addAction($categorie_id)
    {
        $object = new SousCategorie();
        $form = $this->get('form.factory')->create(new SousCategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La sous catégorie a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('sousCategorie_index', ['categorie_id' => $categorie_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:SousCategorie:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($categorie_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($id);
        $form = $this->get('form.factory')->create(new \Trez\LogicielTrezBundle\Form\SousCategorieType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('sousCategorie_index', ['categorie_id' => $categorie_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:SousCategorie:edit.html.twig', array(
            'form' => $form->createView(),
            'sousCategorie' => $object
        ));
    }

    public function deleteAction($categorie_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Sous Catégorie supprimée !');

        return new RedirectResponse($this->generateUrl('categorie_index', ['categorie_id' => $categorie_id]));
    }
}

