<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Ligne;
use Trez\LogicielTrezBundle\Form\LigneType;

class LigneController extends Controller
{
    public function indexAction($sous_categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sous_categorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sous_categorie_id);

        // list only lignes user can read
        $aclFactory = $this->get('trez.logiciel_trez.acl_proxy_factory');
        $lignes = $aclFactory->getSousCategorie($sous_categorie)->getLignes();

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($sous_categorie);

        return $this->render('TrezLogicielTrezBundle:Ligne:list.html.twig', array(
            'lignes' => $lignes,
            'sous_categorie' => $sous_categorie
        ));
    }

    public function addAction($sous_categorie_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $sous_categorie = $em->getRepository('TrezLogicielTrezBundle:SousCategorie')->find($sous_categorie_id);
        $cle = $em->getRepository('TrezLogicielTrezBundle:Ligne')->getLastCle($sous_categorie_id);

        $object = new Ligne();
        $object->setSousCategorie($sous_categorie);
        $object->setCle($cle[0]['cle']+1);

        $form = $this->get('form.factory')->create(new LigneType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $this->get('doctrine.orm.entity_manager')->persist($object);
                $this->get('doctrine.orm.entity_manager')->flush();

                $this->get('session')->setFlash('success', "La ligne a bien été ajoutée");

                return new RedirectResponse($this->generateUrl('ligne_index', array('sous_categorie_id' => $sous_categorie_id)));
            }
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($sous_categorie, 'Ajouter une ligne');

        return $this->render('TrezLogicielTrezBundle:Ligne:add.html.twig', array(
            'form' => $form->createView(),
            'sous_categorie_id' => $sous_categorie_id
        ));
    }

    public function editAction($sous_categorie_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $form = $this->get('form.factory')->create(new LigneType(), $object);

        if ('POST' === $this->get('request')->getMethod()) {
            $form->bindRequest($this->get('request'));
            if ($form->isValid()) {
                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('ligne_index', array('sous_categorie_id' => $sous_categorie_id)));
            }
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($object, 'Modifier la ligne', true);

        return $this->render('TrezLogicielTrezBundle:Ligne:edit.html.twig', array(
            'form' => $form->createView(),
            'ligne' => $object,
            'sous_categorie_id' => $sous_categorie_id
        ));
    }

    public function deleteAction($sous_categorie_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Ligne supprimée !');

        return new RedirectResponse($this->generateUrl('ligne_index', array('sous_categorie_id' => $sous_categorie_id)));
    }

    public function adjustAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($id);
        $ligne->adjustTotal('all');
        $em->flush();

        return new RedirectResponse($this->generateUrl('facture_index', array('ligne_id' => $id)));
    }
}
