<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;

interface FetchStrategyInterface
{
    public function __construct(EntityManager $entity_manager, SecurityContext $security_context);

    public function fetchChildren($entity, $child_name);

    public function getSecurityContext();
}