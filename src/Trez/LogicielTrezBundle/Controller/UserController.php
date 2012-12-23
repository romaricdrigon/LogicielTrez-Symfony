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
        $request = $this->get('request');
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:User')->find($id);
        $form = $this->get('form.factory')->create(new UserEdit(), $object);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
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
        if ($object === null
            || $sc->isGranted('ROLE_ADMIN') === false
            && $sc->getToken()->getUser()->equals($object) === false) {
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

                if ($sc->isGranted('ROLE_ADMIN') === true) {
                    return new RedirectResponse($this->generateUrl('user_index'));
                } else {
                    return new RedirectResponse($this->generateUrl('_welcome'));
                }
            }
        }

        return $this->render('TrezLogicielTrezBundle:User:change_password.html.twig', array(
            'form' => $form->createView(),
            'user' => $object
        ));
    }

    public function deleteAction($id)
    {
        $sc = $this->get('security.context');
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:User')->find($id);

        if (method_exists($sc->getToken()->getUser(), 'equals') === true && $sc->getToken()->getUser()->equals($object) === true) {
            throw new \Exception("Vous ne pouvez pas vous supprimer vous-même !");
        }

        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Utilisateur supprimé !');

        return new RedirectResponse($this->generateUrl('user_index'));
    }
}
