<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Exercice;
use Trez\LogicielTrezBundle\Form\ExerciceType;

class ExerciceController extends Controller
{
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');

        // list only exercices user can read
        $sc = $this->get('security.context');
        if ($sc->isGranted('ROLE_ADMIN') === true) {
            $exercices = $em->getRepository('TrezLogicielTrezBundle:Exercice')->findAll();
        } else if ($sc->isGranted('ROLE_USER') === true && method_exists($sc->getToken()->getUser(), 'getId') === true) {
            $exercices = $em->getRepository('TrezLogicielTrezBundle:Exercice')->getAllowed($sc->getToken()->getUser()->getId());
        } else {
            $exercices = array(); // no exception, user may be authorized here but can't view any budget
        }

        $this->getBreadcrumbs();

        return $this->render('TrezLogicielTrezBundle:Exercice:list.html.twig', array('exercices' => $exercices));
    }

    public function addAction()
    {
        $object = new Exercice();
        $form = $this->get('form.factory')->create(new ExerciceType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "L'exercice a bien été ajouté");

                return new RedirectResponse($this->generateUrl('exercice_index'));
            }
        }

        $this->getBreadcrumbs();

        return $this->render('TrezLogicielTrezBundle:Exercice:add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function editAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($id);
        $form = $this->get('form.factory')->create(new ExerciceType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('exercice_index'));
            }
        }

        $this->getBreadcrumbs();

        return $this->render('TrezLogicielTrezBundle:Exercice:edit.html.twig', array(
            'form' => $form->createView(),
            'exercice' => $object
        ));
    }

    public function deleteAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Exercice supprimé !');

        return new RedirectResponse($this->generateUrl('exercice_index'));
    }

    private function getBreadcrumbs()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Exercices", $this->generateUrl('exercice_index'));
    }
}
