<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use APY\DataGridBundle\Grid\Source\Entity;
use Trez\LogicielTrezBundle\Entity\Exercice;
use Trez\LogicielTrezBundle\Form\ExerciceType;

class ExerciceController extends Controller
{
    public function indexAction()
    {
        // list only exercices user can read
        $aclFactory = $this->get('trez.logiciel_trez.acl_proxy_factory');
        $exercices = $aclFactory->getAll();

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs();

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

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs(false, 'Ajouter un exercice');

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

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs(false, 'Modifier l\'exercice');

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


    public function listFacturesAction($id)
    {
        $source = new Entity('TrezLogicielTrezBundle:Facture');

        $grid = $this->get('grid');
        $grid->setSource($source);

        // configure the grid
        $grid->setPersistence(true);
        $grid->setLimits(array(25, 50, 100, 1000));

        // will both render the template & catch ajax actions
        return $grid->getGridResponse('TrezLogicielTrezBundle:Exercice:grid.html.twig');

        /*$em = $this->get('doctrine.orm.entity_manager');
        $exercice = $em->getRepository('TrezLogicielTrezBundle:Exercice')->find($id);
        $factures = $em->getRepository('TrezLogicielTrezBundle:Exercice')->getFacturesByType($id);
        $typeFactures = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->findAll();
        $templates = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->findBy(
            array('actif' => 1)
        );

        return $this->render('TrezLogicielTrezBundle:Exercice:list_factures.html.twig', array(
            'exercice' => $exercice,
            'factures' => $factures,
            'templates' => $templates,
            'type_factures' => $typeFactures
        ));
        */
    }
}
