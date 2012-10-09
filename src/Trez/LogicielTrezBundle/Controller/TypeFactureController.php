<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\TypeFacture;
use \Trez\LogicielTrezBundle\Form\TypeFactureType;

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
        $typeFacture = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('typeFacture' => $typeFacture));
    }

    public function addAction()
    {
        $object = new TypeFacture();
        $form = $this->get('form.factory')->create(new TypeFactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "Le type de facture a bien été ajouté");

                return new RedirectResponse($this->generateUrl('typeFacture_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:TypeFacture:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->find($id);
        $form = $this->get('form.factory')->create(new TypeFactureType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('typeFacture_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:TypeFacture:edit.html.twig', array(
            'form' => $form->createView(),
            'TypeFacture' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Type de facture supprimé !');

        return new RedirectResponse($this->generateUrl('typeFacture_index'));
    }
}
