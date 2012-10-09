<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Facture;
use Trez\LogicielTrezBundle\Form\FactureType;

class LigneController extends Controller
{

    /*
     * (detail)
     * add
     * edit(ligne_id, id)
     * delete(ligne_id, id)
     */

    public function indexAction($ligne_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $ligne = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($ligne_id);
        $factures = $em->getRepository('TrezLogicielTrezBundle:Ligne')->findOneByBudget($ligne);

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('factures' => $factures));
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
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La facture a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('facture_index', ['ligne_id' => $ligne_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Facture:add.html.twig', array(
            'form' => $form->createView()
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

        return $this->render('TrezLogicielTrezBundle:Facture:edit.html.twig', array(
            'form' => $form->createView(),
            'facture' => $object
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
}