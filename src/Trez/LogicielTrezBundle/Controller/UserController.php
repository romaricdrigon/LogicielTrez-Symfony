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
        $encoder = $this->get('security.encoder_factory')->getEncoder($object);
        $form = $this->get('form.factory')->create(new UserType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                // password field is required so it'll modified anyway
                $password = $encoder->encodePassword($object->getPassword(), $object->getSalt());
                $object->setPassword($password);

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
