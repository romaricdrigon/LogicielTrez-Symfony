<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use \Trez\LogicielTrezBundle\Entity\Tiers;
use \Trez\LogicielTrezBundle\Form\TiersType;

class TiersController extends Controller
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
        $tiers = $em->getRepository('TrezLogicielTrezBundle:Tiers')->findAll();

        return $this->render('TrezLogicielTrezBundle:Default:index.html.twig', array('tiers' => $tiers));
    }

    public function addAction()
    {
        $object = new Tiers();
        $form = $this->get('form.factory')->create(new TiersType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "Le tiers a bien été ajouté");

                return new RedirectResponse($this->generateUrl('tiers_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Tiers:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Tiers')->find($id);
        $form = $this->get('form.factory')->create(new TiersType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos tiers ont été enregistrés');

                return new RedirectResponse($this->generateUrl('tiers_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Tiers:edit.html.twig', array(
            'form' => $form->createView(),
            'tiers' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Tiers')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Tiers supprimé !');

        return new RedirectResponse($this->generateUrl('tiers_index'));
    }
}
