<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\BudgetAclProxy;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\CategorieAclProxy;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\SousCategorieAclProxy;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\LigneAclProxy;

abstract class AbstractBasicAclBuilder extends AbstractAclBuilder
{
    public function build()
    {
        $proxyName = 'Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\\'.$this->getName().'AclProxy';
        $this->proxy = new $proxyName($this->entity);

        $children = array();
        $credit = 0.00;
        $debit = 0.00;

        foreach ($this->proxy->getChildren() as $child) {
            $childAclBuilder = $this->factory->getBuilder($this->getChildName(), $child);

            if ($childAclBuilder->isValid() === true) {
                $childAclProxy = $childAclBuilder->getProxy();
                $children[] = $childAclProxy->getProxied();
                $credit += $childAclProxy->getCredit();
                $debit += $childAclProxy->getDebit();
            }
        }

        $this->proxy->setChildren($children);
        $this->proxy->setCredit($credit);
        $this->proxy->setDebit($debit);
    }

    abstract public function getName();

    abstract public function getChildName();
}