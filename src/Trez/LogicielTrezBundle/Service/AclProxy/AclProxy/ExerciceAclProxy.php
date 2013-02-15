<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclProxy;

use Trez\LogicielTrezBundle\Entity\Exercice;

class ExerciceAclProxy extends Exercice implements ParentInterface, AclProxyInterface
{
    protected $_proxied;

    public function __construct(Exercice $entity)
    {
        $this->_proxied = $entity;
    }

    public function setChildren(array $budgets)
    {
        $this->_proxied->budgets = $budgets;
    }

    public function getChildren()
    {
        return $this->_proxied->budgets;
    }

    public function getProxied()
    {
        return $this->_proxied;
    }
}