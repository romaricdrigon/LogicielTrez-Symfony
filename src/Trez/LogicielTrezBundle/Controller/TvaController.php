<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Tva;
use Trez\LogicielTrezBundle\Form\TvaType;

class LigneController extends Controller
{

    /*
     * (detail)
     * add
     * edit(facture_id, id)
     * delete(facture_id, id)
     */

    public function indexAction($facture_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $facture = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($facture_id);
        $tvas = $em->getRepository('TrezLogicielTrezBundle:Ligne')->findOneByFacture($facture);

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('tvas' => $tvas));
    }

    public function addAction($facture_id)
    {

        $em = $this->get('doctrine.orm.entity_manager');
        $facture = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($facture_id);

        $object = new Tva();
        $object->setFacture($facture);

        $form = $this->get('form.factory')->create(new TvaType(), $tva);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La tva a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('tva_index', ['facture_id' => $facture_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Tva:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($facture_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Tva')->find($id);
        $form = $this->get('form.factory')->create(new TvaType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('tva_index', ['facture_id' => $facture_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Tva:edit.html.twig', array(
            'form' => $form->createView(),
            'tva' => $object
        ));
    }

    public function deleteAction($facture_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Tva')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Tva supprimée !');

        return new RedirectResponse($this->generateUrl('tva_index', ['facture_id' => $facture_id]));
    }
}