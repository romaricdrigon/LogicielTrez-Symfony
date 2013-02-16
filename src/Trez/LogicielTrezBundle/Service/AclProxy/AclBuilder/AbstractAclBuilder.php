<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Symfony\Component\Security\Core\SecurityContext;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxyFactory;

abstract class AbstractAclBuilder
{
    protected $entity;
    protected $factory;
    protected $proxy;

    public function __construct(AclProxyFactory $factory, $entity)
    {
        $this->factory = $factory;
        $this->entity = $entity;
    }

    abstract function build();

    public function isValid()
    {
        $children = $this->proxy->getChildren();

        return ! empty($children);
    }

    public function getProxy()
    {
        return $this->proxy;
    }
}