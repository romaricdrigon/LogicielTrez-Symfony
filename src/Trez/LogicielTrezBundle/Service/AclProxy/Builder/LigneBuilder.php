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
     * Ligne is valid according if containing the current user
     */
    public function isValid()
    {
        $securityContext = $this->strategy->getSecurityContext();

        if ($securityContext->isGranted('ROLE_ADMIN') === true) {
            return true;
        }
        if ($securityContext->isGranted('ROLE_USER') === true
            && $this->entity->getUsers()->contains($securityContext->getToken()->getUser())) {
            return true;
        }

        return false;
    }
}