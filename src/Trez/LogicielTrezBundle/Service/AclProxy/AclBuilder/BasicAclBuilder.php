<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\BudgetAclProxy;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\CategorieAclProxy;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\SousCategorieAclProxy;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\LigneAclProxy;

abstract class BasicAclBuilder extends AclBuilder
{
    public function build()
    {
        $proxyName = 'Trez\LogicielTrezBundle\Service\AclProxy\AclProxy\\'.$this->getName().'AclProxy';
        $this->_proxy = new $proxyName($this->_entity);

        $children = array();
        $credit = 0.00;
        $debit = 0.00;

        foreach ($this->_proxy->getChildren() as $child) {
            $childAclBuilder = $this->_factory->getBuilder($this->getChildName(), $child);

            if ($childAclBuilder->isValid() === true) {
                $childAclProxy = $childAclBuilder->getProxy();
                $children[] = $childAclProxy->getProxied();
                $credit += $childAclProxy->getCredit();
                $debit += $childAclProxy->getDebit();
            }
        }

        $this->_proxy->setChildren($children);
        $this->_proxy->setCredit($credit);
        $this->_proxy->setDebit($debit);
    }

    abstract public function getName();

    abstract public function getChildName();
}