<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\ExerciceAclProxy;

class ExerciceAclBuilder extends AbstractAclBuilder
{
    public function build()
    {
        $this->proxy = new ExerciceAclProxy($this->entity);

        $children = array();

        foreach ($this->proxy->getChildren() as $child) {
            $childAclBuilder = $this->factory->getBuilder('Budget', $child);

            if ($childAclBuilder->isValid() === true) {
                $childAclProxy = $childAclBuilder->getProxy();
                $children[] = $childAclProxy->getProxied();
            }
        }

        $this->proxy->setChildren($children);
    }
}