<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\LigneAclProxy;

class LigneAclBuilder extends AbstractAclBuilder
{
    public function build()
    {
        $this->proxy = new LigneAclProxy($this->entity);
    }

    public function isValid()
    {
        if ($this->factory->getSecurityContext()->isGranted('ROLE_ADMIN') === true) {
            return true;
        }
        if ($this->factory->getSecurityContext()->isGranted('ROLE_USER') === true
            && $this->entity->getUsers()->contains($this->factory->getSecurityContext()->getToken()->getUser())) {
            return true;
        }

        return false;
    }
}