<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Trez\LogicielTrezBundle\Entity\User;
use Trez\LogicielTrezBundle\Form\UserType;
use Trez\LogicielTrezBundle\Form\UserEdit;
use Trez\LogicielTrezBundle\Form\UserPassword;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('TrezLogicielTrezBundle:User')->findAll();

        return $this->render('TrezLogicielTrezBundle:User:list.html.twig', array('users' => $users));
    }

    public function addAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = new User();
        $encoder = $this->get('security.encoder_factory')->getEncoder($object);
        $form = $this->get('form.factory')->create(new UserType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $password = $encoder->encodePassword($object->getPassword(), $object->getSalt());
                $object->setPassword($password);

                $em->persist($object);
                $em->flush();

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
        $form = $this->get('form.factory')->create(new UserEdit(), $object);

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

    // method to edit ONLY the password
    public function changePasswordAction($id)
    {
        $sc = $this->get('security.context');
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:User')->find($id);

        // only and admin or the current user can change its password
        if (! $sc->isGranted('ROLE_ADMIN')
            && ! $sc->getToken()->getUser()->equals($object)) {
            throw new AccessDeniedException();
        }

        $encoder = $this->get('security.encoder_factory')->getEncoder($object);
        $form = $this->get('form.factory')->create(new UserPassword(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $password = $encoder->encodePassword($object->getPassword(), $object->getSalt());
                $object->setPassword($password);

                $em->flush();

                $this->get('session')->setFlash('info', 'Le mot de passe a bien été changé');

                return new RedirectResponse($this->generateUrl('user_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:User:change_password.html.twig', array(
            'form' => $form->createView(),
            'user' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:User')->find($id);

        if ($this->get('security.context')->getToken()->getUser()->equals($object)) {
            throw new \Exception("Vous ne pouvez pas vous supprimer vous-même !");
        }

        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Utilisateur supprimé !');

        return new RedirectResponse($this->generateUrl('user_index'));
    }
}
