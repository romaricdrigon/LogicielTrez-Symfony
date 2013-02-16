<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclProxy;

use Trez\LogicielTrezBundle\Entity\Exercice;

class ExerciceAclProxy extends Exercice implements ParentInterface, AclProxyInterface
{
    protected $proxied;

    public function __construct(Exercice $entity)
    {
        $this->proxied = $entity;
    }

    public function setChildren(array $budgets)
    {
        $this->proxied->budgets = $budgets;
    }

    public function getChildren()
    {
        return $this->proxied->budgets;
    }

    public function getProxied()
    {
        return $this->proxied;
    }
}