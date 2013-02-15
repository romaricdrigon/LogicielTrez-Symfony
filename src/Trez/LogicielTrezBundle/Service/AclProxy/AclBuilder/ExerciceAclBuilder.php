<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\ExerciceAclProxy;

class ExerciceAclBuilder extends AclBuilder
{
    public function build()
    {
        $this->_proxy = new ExerciceAclProxy($this->_entity);

        $children = array();

        foreach ($this->_proxy->getChildren() as $child) {
            $childAclBuilder = $this->_factory->getBuilder('Budget', $child);

            if ($childAclBuilder->isValid() === true) {
                $childAclProxy = $childAclBuilder->getProxy();
                $children[] = $childAclProxy->getProxied();
            }
        }

        $this->_proxy->setChildren($children);
    }
}