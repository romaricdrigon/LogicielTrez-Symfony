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
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {

                $this->get('doctrine.orm.entity_manager')->persist($object);
                //We test if there's already one default template and we solve the problem
                $this->checkDefaultTemplate($object->getId(), $object->getType());
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "Le template de facture a bien été ajouté");

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
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {

                //We test if there's already one default template and we solve the problem
                $this->checkDefaultTemplate($id, $object->getType());

                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

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

        $this->get('session')->setFlash('info', 'Template de facture supprimé !');
        $em->remove($object);
        $em->flush();

        return new RedirectResponse($this->generateUrl('config_index')."#template");
    }

    private function checkDefaultTemplate($id, $type)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $template = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->getDefaultTemplate($type);

        if( $template == null )
        {
            //If there's no default template, then no problem
            return true;
        }
        //If there's one, we check if it's the one we update
        if($template->getId() == $id)
        {
            //If this is true, then no problem
            return true;

        }
        //Else we remove the default option to the oldest template
        $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->setNotDefaultTemplate($template->getId());
    }
}
