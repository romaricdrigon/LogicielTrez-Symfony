<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BudgetController extends Controller
{
    /*
     * (detail)
     * add
     * edit(exercice_id, id)
     * delete(exercice_id, id)
     */

    public function indexAction($exercice_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $budgets = $em->getRepository('TrezLogicielTrezBundle:Budget')->findBy(['exercice_id' => $exercice_id]);

        return $this->render('TrezLogicielTrezBundle:Budget:list.html.twig', ['budgets' => $budgets]);
    }

    public function addAction($exercice_id)
    {
        $object = new Budget();
        $form = $this->get('form.factory')->create(new BudgetType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "Le budget a bien été ajouté");

                return new RedirectResponse($this->generateUrl('budget_index', ['exercice_id' => $exercice_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Budget:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($exercice_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($id);
        $form = $this->get('form.factory')->create(new BudgetType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('budget_index', ['exercice_id' => $exercice_id]));
            }
        }

        return $this->render('TrezLogicielTrezBundle:Budget:edit.html.twig', array(
            'form' => $form->createView(),
            'budget' => $object
        ));
    }

    public function deleteAction($exercice_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Budget supprimé !');

        return new RedirectResponse($this->generateUrl('budget_index', ['exercice_id' => $exercice_id]));
    }
}
