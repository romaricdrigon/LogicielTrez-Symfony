<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\User;
use Trez\LogicielTrezBundle\Form\UserType;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('TrezLogicielTrezBundle:User')->findAll();

        return $this->render('TrezLogicielTrezBundle:Exercice:list.html.twig', array('users' => $users));
    }

    public function addAction()
    {
        $object = new User();
        $form = $this->get('form.factory')->create(new UserType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "L'utilisateur a bien été ajouté");

                return new RedirectResponse($this->generateUrl('user_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:User:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:User')->find($id);
        $form = $this->get('form.factory')->create(new UserType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('user_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:User:edit.html.twig', array(
            'form' => $form->createView(),
            'user' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:User')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Utilisateur supprimé !');

        return new RedirectResponse($this->generateUrl('user_index'));
    }
}
