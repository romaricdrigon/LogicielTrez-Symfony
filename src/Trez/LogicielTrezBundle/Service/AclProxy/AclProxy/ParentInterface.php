<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclProxy;

interface ParentInterface
{
    public function setChildren(array $children);

    public function getChildren();
}