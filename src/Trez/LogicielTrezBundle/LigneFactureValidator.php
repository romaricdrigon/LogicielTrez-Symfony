<?php
namespace Trez\LogicielTrezBundle;

use Symfony\Component\Validator\ExecutionContext;
use Trez\LogicielTrezBundle\Entity\Facture;
use Trez\LogicielTrezBundle\Entity\Ligne;

class LigneFactureValidator
{
    public function isTotalUnder(Facture $facture, ExecutionContext $context)
    {
        //$em = $this->get('doctrine.orm.entity_manager');

        $ligne_total = $facture->getLigneTotal();

        /*$ligne = $facture->getLigne();
        $ligne = $em->getRepository('TrezLogicielTrezBundle:Ligne')->find($ligne_id);
        $factures = $em->getRepository('TrezLogicielTrezBundle:Facture')->findBy(
            ['ligne' => $ligne],
            ['numero' => 'DESC']);*/

        if ($facture->getMontant() > $ligne_total) {
            $context->addViolationAtSubPath('montant', 'Le montant ne peut pas dépasser de la ligne, soit %ligne_total% € au maximum', ['%ligne_total%' => $ligne_total], null);
        }
    }
}