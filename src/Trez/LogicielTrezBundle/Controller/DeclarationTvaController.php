<?php

namespace Trez\LogicielTrezBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;

class DeclarationTvaController extends Controller
{
    public function indexAction()
    {
        $defaultData = array('message' => 'Je ne sais pas à quoi cela sert');
        $form = $this->createFormBuilder($defaultData)
            ->add('mois', 'date')
            ->getForm();

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:list.html.twig', array(
                'form' => $form
            ));
    }

    public function listFacturesAction($date)
    {
        if ($date == -1) {

        }

        return new Response();
    }

    public function createAction()
    {
        return new Response();
    }

    public function generateSheetAction($id)
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $declaration = $em->getRepository('TrezLogicielTrezBundle:DeclarationTva')->find($id);
        $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(
            array('declarationTva' => $declaration)
        );
        //Création du tableau javascript qui va être affiché
        $data="";
        $i = 0;
        foreach ($factures as $facture)
        {
            $data .= '
                data['.$i.'] = {
                numero: "'.$facture->getId().'",
                objet: "'.$facture->getObjet().'",
                montant: "'.$facture->getMontant().'",
                date: "'.$facture->getDatePaiement()->format('d-m-Y').'"
                };
            ';
            $i++;
        }

        return $this->render('TrezLogicielTrezBundle:DeclarationTva:generateSheet.html.twig', array(
            'declaration' => $declaration,
            'factures' => $factures,
            'data' => $data,
            'nombreLigne' => $i
        ));
    }
}
