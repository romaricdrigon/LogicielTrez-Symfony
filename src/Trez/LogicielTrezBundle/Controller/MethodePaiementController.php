<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\MethodePaiement;
use \Trez\LogicielTrezBundle\Form\MethodePaiementType;

class ConfigController extends Controller
{
    /*
     * (detail)
     * add
     * edit(id)
     * delete(id)
     */

    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $methodePaiement = $em->getRepository('TrezLogicielTrezBundle:MethodePaiement')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('configs' => $methodePaiement));
    }

    public function addAction()
    {
        $object = new MethodePaiement();
        $form = $this->get('form.factory')->create(new MethodePaiementType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La méthode de paiement a bien été ajouté");

                return new RedirectResponse($this->generateUrl('methodePaiement_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:MethodePaiement:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:MethodePaiement')->find($id);
        $form = $this->get('form.factory')->create(new MethodePaiementType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('methodePaiement_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:MethodePaiement:edit.html.twig', array(
            'form' => $form->createView(),
            'methodePaiement' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:MethodePaiement')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Methode de paiement supprimée !');

        return new RedirectResponse($this->generateUrl('methodePaiement_index'));
    }
}
