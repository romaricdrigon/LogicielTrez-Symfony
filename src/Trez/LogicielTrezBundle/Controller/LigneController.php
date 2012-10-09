<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Ligne;
use Trez\LogicielTrezBundle\Form\LigneType;

class LigneController extends Controller
{

    /*
     * (detail)
     * add
     * edit(sousCategorie_id, id)
     * delete(sousCategorie_id, id)
     */

    public function indexAction($sousCategorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sousCategorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sousCategorie_id);
        $lignes = $em->getRepository('TrezLogicielTrezBundle:Ligne')->findOneByBudget($sousCategorie);

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('lignes' => $lignes));
    }

    public function addAction($sousCategorie_id)
    {
        $object = new Ligne();
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
            'form' => $form->createView()
        ));
    }

    public function editAction($sousCategorie_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $form = $this->get('form.factory')->create(new LigneType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('ligne_index', ['sousCategorie_id' => $sousCategorie_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Ligne:edit.html.twig', array(
            'form' => $form->createView(),
            'ligne' => $object
        ));
    }

    public function deleteAction($sousCategorie_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Ligne supprimée !');

        return new RedirectResponse($this->generateUrl('ligne_index', ['sousCategorie_id' => $sousCategorie_id]));
    }
}