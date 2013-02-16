<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

use Doctrine\Common\Collections\ArrayCollection;

class LigneBuilder extends AbstractBuilder
{
    public function buildChildren()
    {
        // we don't have to modify Factures at the moment
    }

    public function buildTotaux() {
        // credit and debit are already properties
    }

    /*
     * Composite nodes are valid if they have children
     * We are working with Doctrine ArrayCollection
     */
    public function isValid()
    {
        // TODO: check security context
    }
}