<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\LigneAclProxy;

class LigneAclBuilder extends AclBuilder
{
    public function build()
    {
        $this->_proxy = new LigneAclProxy($this->_entity);
    }

    public function isValid()
    {
        if ($this->_factory->getSecurityContext()->isGranted('ROLE_ADMIN') === true) {
            return true;
        }
        if ($this->_factory->getSecurityContext()->isGranted('ROLE_USER') === true
            && $this->_entity->getUsers()->contains($this->_factory->getSecurityContext()->getToken()->getUser())) {
            return true;
        }

        return false;
    }
}