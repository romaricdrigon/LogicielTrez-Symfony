<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Trez\LogicielTrezBundle\Entity\Facture;
use Trez\LogicielTrezBundle\Form\FactureType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class FactureController extends Controller
{
    public function indexAction($ligne_id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);

        // list only factures user can read
        $aclFactory = $this->get('trez.logiciel_trez.acl_proxy_factory');
        $factures = $aclFactory->getLigne($ligne)->getFactures();

        $templates = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->findBy(
            array('actif' => 1)
        );
        $type_factures = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->findAll();

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($ligne);

        return $this->render('TrezLogicielTrezBundle:Facture:list.html.twig', array(
            'factures' => $factures,
            'ligne' => $ligne,
            'ligne_totals' => $ligne->getTotals(),
            'type_factures' => $type_factures,
            'templates' => $templates
        ));
    }

    public function detailAction($ligne_id, $id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);
        $tvas = $em->getRepository('TrezLogicielTrezBundle:Tva')->findBy(
            array('facture' => $object)
        );

        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);

        // check if user is ok
        $sc = $this->get('security.context');
        if ($sc->isGranted('ROLE_ADMIN') === false && $sc->isGranted('ROLE_USER') === true) {
            if (method_exists($sc->getToken()->getUser(), 'isLigneAllowed') === true
                && $sc->getToken()->getUser()->isLigneAllowed($ligne) === true) {
                // inversed if, otherwise a bit hard to read!
            } else {
                throw new AccessDeniedException();
            }
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($object, 'Détail de la facture', true);

        return $this->render('TrezLogicielTrezBundle:Facture:detail.html.twig', array(
            'facture' => $object,
            'ligne_id' => $ligne_id,
            'tvas' => $tvas
        ));
    }

    public function addAction($ligne_id, $type_id)
    {
        $request = $this->get('request');
        $em = $this->get('doctrine.orm.entity_manager');
        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);

        $object = new Facture();
        $object->setLigne($ligne);

        // take care of type facture and numerotation
        $type_facture = $em->getRepository('TrezLogicielTrezBundle:TypeFacture')->find($type_id);
        $object->setTypeFacture($type_facture);

        $exercice = $ligne->getSousCategorie()->getCategorie()->getBudget()->getExercice();
        $last_numero = $em->getRepository('TrezLogicielTrezBundle:Exercice')->getLastFactureNumero($exercice->getId(), $type_id);
        $object->setNumero($last_numero[0]['numero']+1);

        if ('POST' === $this->get('request')->getMethod()) {
            // if user asked to adjust the ligne total, we disable some validation
            if ($request->request->get('adjust', false)) {
                $form = $this->get('form.factory')->create(new FactureType(), $object, array('validation_groups' => array('Default')));
            } else {
                $form = $this->get('form.factory')->create(new FactureType(), $object);
            }

            $form->bindRequest($this->get('request'));

            if ($form->isValid()) {
                $em->persist($object);
                $em->flush();

                // if user asked to adjust the ligne total just do it
                if ($request->request->get('adjust', false)) {
                    $object->getLigne()->adjustTotal($object->getTypeFacture()->getSens());
                    $em->flush(); // we have to flush before AND after, a second time
                }

                $this->get('session')->setFlash('success', "La facture a bien été émise");

                return new RedirectResponse($this->generateUrl('facture_index', array('ligne_id' => $ligne_id)));
            }
        } else {
            $form = $this->get('form.factory')->create(new FactureType(), $object);
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($ligne, 'Ajouter une facture');

        return $this->render('TrezLogicielTrezBundle:Facture:add.html.twig', array(
            'form' => $form->createView(),
            'ligne_id' => $ligne_id,
            'type_facture_id' => $type_id
        ));
    }

    public function editAction($ligne_id, $id)
    {
        $request = $this->get('request');
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);

        // get the original TVAs
        $oTvas = array();
        foreach ($object->getTvas() as $tva) {
            $oTvas[] = $tva;
        }

        if ('POST' === $request->getMethod()) {
            // if user asked to adjust the ligne total, we disable some validation
            if ($request->request->get('adjust', false)) {
                $form = $this->get('form.factory')->create(new FactureType(), $object, array('validation_groups' => array('Default')));
            } else {
                $form = $this->get('form.factory')->create(new FactureType(), $object);
            }

            $form->bindRequest($request);

            if ($form->isValid()) {
                // if user asked to adjust the ligne total just do it
                if ($request->request->get('adjust', false)) {
                    $object->getLigne()->adjustTotal($object->getTypeFacture()->getSens());
                }

                // remove removed TVAs
                foreach ($object->getTvas() as $nTva) {
                    foreach ($oTvas as $key => $oTva) {
                        if ($oTva->getId() === $nTva->getId()) {
                            unset($oTvas[$key]);
                        }
                    }
                }
                foreach ($oTvas as $tvaToDelete) {
                    $em->remove($tvaToDelete);
                }

                $em->flush();

                $this->get('session')->setFlash('info', 'Vos modifications ont été enregistrées');

                return new RedirectResponse($this->generateUrl('facture_index', array('ligne_id' => $ligne_id)));
            }
        } else {
            $form = $this->get('form.factory')->create(new FactureType(), $object);
        }

        $this->get('trez.logiciel_trez.breadcrumbs')->setBreadcrumbs($object, 'Éditer la facture', true);

        return $this->render('TrezLogicielTrezBundle:Facture:edit.html.twig', array(
            'form' => $form->createView(),
            'facture' => $object,
            'ligne_id' => $ligne_id
        ));
    }
    
    public function printAction($ligne_id, $id, $template_id)
    {
    	$em = $this->get('doctrine.orm.entity_manager');
    	$object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);
    	$tvas = $em->getRepository('TrezLogicielTrezBundle:Tva')->findBy(
    			array('facture' => $object)
    	);
        $config = $em->getRepository('TrezLogicielTrezBundle:Config')->findAll();

        $template = $em->getRepository('TrezLogicielTrezBundle:TemplateFacture')->find($template_id);

    	$totalTTC = $object->getMontant();
    	foreach ($tvas as $tva)
    	{
    		$totalTTC += $tva->getMontantTVA();
    	}
    	return $this->render('TrezLogicielTrezBundle:Facture:print.html.twig', array(
    			'facture' => $object,
    			'tvas' => $tvas, 
    			'totalTTC' => $totalTTC,
                'template' => $template,
                'currency' => $config[0],
                'config' =>$config
    	));
    }

    public function deleteAction($ligne_id ,$id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $object = $em->getRepository('TrezLogicielTrezBundle:Facture')->find($id);
        $em->remove($object);
        $em->flush();

        $this->get('session')->setFlash('info', 'Facture supprimée !');

        return new RedirectResponse($this->generateUrl('facture_index', array('ligne_id' => $ligne_id)));
    }
}