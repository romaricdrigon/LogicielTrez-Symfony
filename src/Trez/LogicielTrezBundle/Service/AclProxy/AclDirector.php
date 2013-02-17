<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy;

use Trez\LogicielTrezBundle\Service\AclProxy\Builder\BuilderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AclDirector
{
    protected $builder;
    protected $entity;
    protected $built = false;

    public function __construct(BuilderInterface $builder)
    {
        $this->builder = $builder;
    }

    public function constructEntity($strict = true)
    {
        $this->builder->buildChildren();

        if ($this->builder->isValid() === true) {
            $this->builder->buildTotaux();
            $this->entity = $this->builder->getResult();
            $this->built = true;
        } else if ($strict === true) {
            throw new AccessDeniedException();
        }

        return $this; // follow fluent interface rules
    }

    public function getEntity()
    {
        if ($this->built === true) {
            return $this->entity;
        } else {
            return false;
        }
    }
}
