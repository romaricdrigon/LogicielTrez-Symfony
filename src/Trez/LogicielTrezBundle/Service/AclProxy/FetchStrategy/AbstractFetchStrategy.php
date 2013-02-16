<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

abstract class AbstractFetchStrategy implements FetchStrategyInterface
{
    protected $entityManager;
    protected $securityContext;

    public function __construct(EntityManager $entity_manager, SecurityContext $security_context)
    {
        $this->entityManager = $entity_manager;
        $this->securityContext = $security_context;
    }

    public function getSecurityContext()
    {
        return $this->securityContext;
    }

    abstract public function fetchChildren($entity, $child_name);
}