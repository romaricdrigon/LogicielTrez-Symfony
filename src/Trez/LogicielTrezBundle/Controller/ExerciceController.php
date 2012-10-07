<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Exercice;
use Trez\LogicielTrezBundle\Form\ExerciceType;

class ExerciceController extends Controller
{
    /*
     * edit(id)
     * delete(id)
     */

    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $exercices = $em->getRepository('TrezLogicielTrezBundle:Exercice')->findAll();

        return $this->render('TrezLogicielTrezBundle:Exercice:list.html.twig', array('exercices' => $exercices));
    }

    public function addAction()
    {
        $proto = new Exercice();
        $form = $this->get('form.factory')->create(new ExerciceType(), $proto);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($proto);
                $this->get('doctrine.orm.entity_manager')->flush();

                //$this->get('session')->setFlash('success', 'La source de données a été créée avec succès');

                return new RedirectResponse($this->generateUrl('exercice_index'));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Exercice:add.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
