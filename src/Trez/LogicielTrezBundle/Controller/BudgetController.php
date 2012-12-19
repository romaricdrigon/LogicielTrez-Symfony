<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Trez\LogicielTrezBundle\Entity\Budget;
use Trez\LogicielTrezBundle\Form\BudgetType;

class BudgetController extends Controller
{
    public function indexAction($exercice_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $exercice = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($exercice_id);

        // list only budgets user can read
        $sc = $this->get('security.context');
        if ($sc->isGranted('ROLE_USER') === true && method_exists($sc->getToken()->getUser(), 'getId') === true) {
            $budgets = $em->getRepository('TrezLogicielTrezBundle:Budget')->getAllowed($exercice_id, $sc->getToken()->getUser()->getId());

            if ($budgets === array()) {
                throw new AccessDeniedException();
            }
        } else {
            $budgets = $em->getRepository('TrezLogicielTrezBundle:Budget')->getAll($exercice_id);
        }

        $this->getBreadcrumbs($exercice);

        return $this->render('TrezLogicielTrezBundle:Budget:list.html.twig', array(
            'budgets' => $budgets,
            'exercice' => $exercice
        ));
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

                return new RedirectResponse($this->generateUrl('budget_index', array('exercice_id' => $exercice_id)));
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

                return new RedirectResponse($this->generateUrl('budget_index', array('exercice_id' => $exercice_id)));
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

    public function duplicateAction($id, $exercice_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $old_budget = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($id);

        $new_budget = $old_budget->duplicate();
        $em->persist($new_budget);

        // we have to do the deep copy here
        // to persist each time children entities
        foreach ($old_budget->getCategories() as $old_categorie) {
            $new_categorie = $old_categorie->duplicate();
            $new_categorie->setBudget($new_budget);
            $em->persist($new_categorie);

            foreach ($old_categorie->getSousCategories() as $old_sous_categorie) {
                $new_sous_categorie = $old_sous_categorie->duplicate();
                $new_sous_categorie->setCategorie($new_categorie);
                $em->persist($new_sous_categorie);

                foreach ($old_sous_categorie->getLignes() as $old_ligne) {
                    $new_ligne = $old_ligne->duplicate();
                    $new_ligne->setSousCategorie($new_sous_categorie);
                    $em->persist($new_ligne);
                }
            }
        }

        $em->flush();

        $this->get('session')->setFlash('success', "Ce budget a bien été sauvegardé/dupliqué");

        return new RedirectResponse($this->generateUrl('budget_index', array('exercice_id' => $exercice_id)));
    }

    public function deleteAction($exercice_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Budget')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Budget supprimé !');

        return new RedirectResponse($this->generateUrl('budget_index', array('exercice_id' => $exercice_id)));
    }

    private function getBreadcrumbs($exercice)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercices", $this->generateUrl('exercice_index'));
        $breadcrumbs->addItem("Budgets de ".$exercice->getEdition(), $this->generateUrl('budget_index', array('exercice_id' => $exercice->getId())));
    }
}
