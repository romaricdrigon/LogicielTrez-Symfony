<?php
namespace Trez\LogicielTrezBundle;

use Symfony\Component\Validator\ExecutionContext;
use Trez\LogicielTrezBundle\Entity\Facture;
use Trez\LogicielTrezBundle\Entity\Ligne;

class LigneFactureValidator
{
    public function isTotalUnder(Facture $facture, ExecutionContext $context)
    {
        $ligne_total = $facture->getLigneTotal();

        if ($facture->getMontant() > $ligne_total) {
            $context->addViolationAtSubPath('montant', 'Le montant ne peut pas dépasser de la ligne, soit %ligne_total% € au maximum', array('%ligne_total%' => $ligne_total), null);
        }
    }
}