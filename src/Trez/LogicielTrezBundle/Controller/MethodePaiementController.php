<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\MethodePaiement;
use \Trez\LogicielTrezBundle\Form\MethodePaiementType;

class MethodePaiementController extends Controller
{
    /*
     * (detail)
     * add
     * edit(id)
     * delete(id)
     */

    //not used cf config_index
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $methodePaiement = $em->getRepository('TrezLogicielTrezBundle:MethodePaiement')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('methodePaiement' => $methodePaiement));
    }

    public function addAction()
    {
        $object = new MethodePaiement();
        $form = $this->get('form.factory')->create(new MethodePaiementType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->getFlashBag()->set('success', "La méthode de paiement a bien été ajouté");

                return new RedirectResponse($this->generateUrl('config_index')."#paiement");
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
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->getFlashBag()->set('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('config_index')."#paiement");
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
            //We test if this object is used, if not we can delete it
        $isUsed = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(array('methodePaiement' => $object));
        if ($isUsed == null)
        {
        	$this->get('session')->getFlashBag()->set('info', 'Méthode de paiement supprimée !');
        	$em->remove($object);
        	$em->flush();
        }else{
        	$this->get('session')->getFlashBag()->set('error', 'Cette méthode de paiement ne peut pas être supprimée !');
        }

        return new RedirectResponse($this->generateUrl('config_index')."#paiement");
    }
}
