<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\TemplateFacture;
use \Trez\LogicielTrezBundle\Form\TemplateFactureType;

class TemplateFactureController extends Controller
{
    /*
     * (detail)
     * add
     * edit(id)
     * delete(id)
     */

    public function indexAction() //not used
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $template = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('template' => $template));
    }

    public function addAction()
    {
        $object = new TemplateFacture();
        $form = $this->get('form.factory')->create(new TemplateFactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {

                $this->get('doctrine.orm.entity_manager')->persist($object);

                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->getFlashBag()->set('success', "Le template de facture a bien été ajouté");

                return new RedirectResponse($this->generateUrl('config_index')."#template");
            }
        }

        return $this->render('TrezLogicielTrezBundle:TemplateFacture:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->find($id);
        $form = $this->get('form.factory')->create(new TemplateFactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {

                $em->flush();

                $this->get('session')->getFlashBag()->set('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('config_index')."#template");
            }
        }

        return $this->render('TrezLogicielTrezBundle:TemplateFacture:edit.html.twig', array(
            'form' => $form->createView(),
            'TemplateFacture' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->find($id);

        $this->get('session')->getFlashBag()->set('info', 'Template de facture supprimé !');
        $em->remove($object);
        $em->flush();

        return new RedirectResponse($this->generateUrl('config_index')."#template");
    }

}
