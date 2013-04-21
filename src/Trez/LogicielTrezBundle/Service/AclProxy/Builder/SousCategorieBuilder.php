<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

class SousCategorieBuilder extends AbstractCompositeBuilder
{
    public function getName()
    {
        return 'SousCategorie';
    }

    public function getChildName()
    {
        return 'Ligne';
    }

    /*
     * Override parent, as lignes uses getters (private properties)
     */
    public function buildTotaux() {
        $credit = 0.00;
        $debit = 0.00;

        foreach ($this->entity->{'get'.$this->getChildName().'s'}() as $child) {
            $credit += $child->getCredit();
            $debit += $child->getDebit();
        }

        $this->entity->credit = $credit; // will create such a public property
        $this->entity->debit = $debit;
    }
}
