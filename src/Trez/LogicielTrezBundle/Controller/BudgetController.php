<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Budget;
use Trez\LogicielTrezBundle\Form\BudgetType;

class BudgetController extends Controller
{
    public function indexAction($exercice_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $exercice = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($exercice_id);
        $budgets = $em->getRepository('TrezLogicielTrezBundle:Budget')->getAll($exercice);

        $this->getBreadcrumbs($exercice);

        return $this->render('TrezLogicielTrezBundle:Budget:list.html.twig', [
            'budgets' => $budgets,
            'exercice' => $exercice
        ]);
    }

    public function addAction($exercice_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $exercice = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($exercice_id);

        $budget = new Budget();
        $budget->setExercice($exercice);

        $form = $this->get('form.factory')->create(new BudgetType(), $budget);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $em->persist($budget);
                $em->flush();

                $this->get('session')->setFlash('success', "Le budget a bien été ajouté");

                return new RedirectResponse($this->generateUrl('budget_index', ['exercice_id' => $exercice_id]));
            }
        }

        $this->getBreadcrumbs($exercice);

        return $this->render('TrezLogicielTrezBundle:Budget:add.html.twig', array(
            'form' => $form->createView(),
            'exercice_id' => $exercice_id
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

        $exercice = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($exercice_id);
        $this->getBreadcrumbs($exercice);

        return $this->render('TrezLogicielTrezBundle:Budget:edit.html.twig', array(
            'form' => $form->createView(),
            'budget' => $object,
            'exercice_id' => $exercice_id
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

    private function getBreadcrumbs($exercice)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercices", $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Budgets de ".$exercice->getEdition(), $this->generateUrl('budget_index', ['exercice_id' => $exercice->getId()]));
    }
}
