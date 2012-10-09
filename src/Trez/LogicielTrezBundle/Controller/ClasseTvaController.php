<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\ClasseTva;
use \Trez\LogicielTrezBundle\Form\ClasseTvaType;

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
        $classeTvas = $em->getRepository('TrezLogicielTrezBundle:ClasseTva')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('configs' => $classeTvas));
    }

    public function addAction()
    {
        $object = new ClasseTva();
        $form = $this->get('form.factory')->create(new ClasseTvaType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La classe de TVA a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('classeTva_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:ClasseTva:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:ClasseTva')->find($id);
        $form = $this->get('form.factory')->create(new ClasseTvaType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('classeTva_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:ClasseTva:edit.html.twig', array(
            'form' => $form->createView(),
            'classeTva' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:ClasseTva')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Classe de TVA supprimée !');

        return new RedirectResponse($this->generateUrl('classeTva_index'));
    }
}
