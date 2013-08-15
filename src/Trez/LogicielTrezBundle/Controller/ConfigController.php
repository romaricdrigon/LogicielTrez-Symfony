<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\Config;
use \Trez\LogicielTrezBundle\Form\ConfigType;

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
        $configs = $em->getRepository('TrezLogicielTrezBundle:Config')->findAll();
        $tvas = $em->getRepository('TrezLogicielTrezBundle:ClasseTva')->findAll();
        $paiements = $em->getRepository('TrezLogicielTrezBundle:MethodePaiement')->findAll();
        $factures = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->findAll();
        $templates = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->findAll();

        return $this->render('TrezLogicielTrezBundle:Config:list.html.twig', 
        	array(	'configs' => $configs,
        			'tvas' => $tvas,		
        			'paiements' => $paiements,
        			'factures' => $factures,
                    'templates' => $templates
        	));

    }

    public function addAction()
    {
        $object = new Config();
        $form = $this->get('form.factory')->create(new ConfigType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->getFlashBag()->set('success', "La config a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('config_index')."#general");
            }
        }

        return $this->render('TrezLogicielTrezBundle:Config:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Config')->find($id);
        $form = $this->get('form.factory')->create(new ConfigType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->handleRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->getFlashBag()->set('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('config_index')."#general");
            }
        }

        return $this->render('TrezLogicielTrezBundle:Config:edit.html.twig', array(
            'form' => $form->createView(),
            'config' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Config')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->getFlashBag()->set('info', 'Config supprimée !');

        return new RedirectResponse($this->generateUrl('config_index')."#general");
    }
}
