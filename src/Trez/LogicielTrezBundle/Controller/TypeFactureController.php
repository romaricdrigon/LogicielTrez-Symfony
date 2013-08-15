<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\TypeFacture;
use \Trez\LogicielTrezBundle\Form\TypeFactureType;

class TypeFactureController extends Controller
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
        $typeFacture = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('typeFacture' => $typeFacture));
    }

    public function addAction()
    {
        $object = new TypeFacture();
        $form = $this->get('form.factory')->create(new TypeFactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->getFlashBag()->set('success', "Le type de facture a bien été ajouté");

                return new RedirectResponse($this->generateUrl('config_index')."#facture");
            }
        }

        return $this->render('TrezLogicielTrezBundle:TypeFacture:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->find($id);
        $form = $this->get('form.factory')->create(new TypeFactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->getFlashBag()->set('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('config_index')."#facture");
            }
        }

        return $this->render('TrezLogicielTrezBundle:TypeFacture:edit.html.twig', array(
            'form' => $form->createView(),
            'TypeFacture' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->find($id);
        //We test if this object is used, if not we can delete it
        $isUsed = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(array('typeFacture' => $object));
        if ($isUsed == null)
        {
        	$this->get('session')->getFlashBag()->set('info', 'Type de facture supprimé !');
        	$em->remove($object);
        	$em->flush();
        }else{
        	$this->get('session')->getFlashBag()->set('error', 'Cet type de facture ne peut pas être supprimée !');
        }

        return new RedirectResponse($this->generateUrl('config_index')."#facture");
    }
}
