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

        return $this->render('TrezLogicielTrezBundle:Ligne:list.html.twig', [
            'lignes' => $lignes,
            'sous_categorie' => $sous_categorie
        ]);
    }

    public function addAction($sous_categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sousCategorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sous_categorie_id);

        $object = new Ligne();
        $object->setSousCategorie($sousCategorie);

        $form = $this->get('form.factory')->create(new LigneType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La ligne a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('ligne_index', ['sousCategorie_id' => $sousCategorie_id]));
            }
        }

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
}