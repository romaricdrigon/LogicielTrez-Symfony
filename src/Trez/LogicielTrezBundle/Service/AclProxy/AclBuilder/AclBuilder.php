<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

use Symfony\Component\Security\Core\SecurityContext;
use Trez\LogicielTrezBundle\Service\AclProxy\AclProxyFactory;

abstract class AclBuilder
{
    protected $_entity;
    protected $_factory;
    protected $_proxy;

    public function __construct(AclProxyFactory $factory, $entity)
    {
        $this->_factory = $factory;
        $this->_entity = $entity;
    }

    abstract function build();

    public function isValid()
    {
        $children = $this->_proxy->getChildren();

        return ! empty($children);
    }

    public function getProxy()
    {
        return $this->_proxy;
    }
}