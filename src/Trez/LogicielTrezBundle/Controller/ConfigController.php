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

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('configs' => $configs));
    }

    public function addAction()
    {
        $object = new Config();
        $form = $this->get('form.factory')->create(new ConfigType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La config a bien été ajouté");

                return new RedirectResponse($this->generateUrl('config_index'));
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
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('config_index'));
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

        $this->get('session')->setFlash('info', 'Config supprimée !');

        return new RedirectResponse($this->generateUrl('config_index'));
    }
}
