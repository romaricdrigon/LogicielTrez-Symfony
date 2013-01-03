<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Trez\LogicielTrezBundle\Entity\User;
use Trez\LogicielTrezBundle\Form\UserType;
use Trez\LogicielTrezBundle\Form\UserEdit;
use Trez\LogicielTrezBundle\Form\UserPassword;
use Trez\LogicielTrezBundle\Form\UserPasswordAdmin;

class UserController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $users = $em->getRepository('TrezLogicielTrezBundle:User')->findAll();

        if ($this->container->hasParameter('users') && is_array($this->container->getParameter('users')) === true) {
            $memory_users = array_keys($this->container->getParameter('users'));
        } else {
            $memory_users = array();
        }

        return $this->render('TrezLogicielTrezBundle:User:list.html.twig', array(
            'users' => $users,
            'memory_users' => $memory_users
        ));
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
        $old_mail = $object->getMail();
        $form = $this->get('form.factory')->create(new UserEdit(), $object);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                if ($object->getMail() !== $old_mail) {
                    // delete associated OpenIdIdentities
                    foreach ($object->getOpenIdIdentities() as $identity) {
                        $em->remove($identity);
                    }
                }
                
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

        if ($object === null) {
            throw new AccessDeniedException();
        }

        // next it depend on the role
        if ($sc->isGranted('ROLE_ADMIN') === true) {
            return $this->changePasswordAdmin($object);
        } else if ($sc->getToken()->getUser()->equals($object) === true && $sc->getToken()->getUser()->getCanCredentials() === true) {
            return $this->changePasswordUser($object);
        }

        throw new AccessDeniedException(); // only an admin or the current user can change its password
    }
    // for admins only
    private function changePasswordAdmin(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        $sc = $this->get('security.context');
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');

        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $form = $this->get('form.factory')->create(new UserPasswordAdmin(), $user);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);

                $em->flush();

                $this->get('session')->setFlash('info', 'Le mot de passe a bien été changé');

                return new RedirectResponse($this->generateUrl('user_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:User:change_password.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'cancel_link' => $this->generateUrl('user_index')
        ));
    }
    // for "normal" users
    private function changePasswordUser(\Symfony\Component\Security\Core\User\UserInterface $user)
    {
        $sc = $this->get('security.context');
        $em = $this->get('doctrine.orm.entity_manager');
        $request = $this->container->get('request');

        $old_password = $user->getPassword();

        $encoder = $this->get('security.encoder_factory')->getEncoder($user);
        $form = $this->get('form.factory')->create(new UserPassword(), $user);

        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                // first we check if the old password is correct
                $proof_password = $form->get('old_password')->getData();

                if ($old_password !== $encoder->encodePassword($proof_password, $user->getSalt())) {
                    $this->get('session')->setFlash('error', "L'ancien mot de passe est incorrect");

                    return $this->render('TrezLogicielTrezBundle:User:change_password.html.twig', array(
                        'form' => $form->createView(),
                        'user' => $user,
                        'cancel_link' => $this->generateUrl('_welcome')
                    ));
                }

                // now changes
                $password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
                $user->setPassword($password);

                $em->flush();

                $this->get('session')->setFlash('info', 'Le mot de passe a bien été changé');

                return new RedirectResponse($this->generateUrl('_welcome'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:User:change_password.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
            'cancel_link' => $this->generateUrl('_welcome')
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

        // don't forget to delete associated OpenIdIdentities
        foreach ($object->getOpenIdIdentities() as $identity) {
            $em->remove($identity);
        }

        $em->remove($object);

        $em->flush();

        $this->get('session')->setFlash('info', 'Utilisateur supprimé !');

        return new RedirectResponse($this->generateUrl('user_index'));
    }
}
