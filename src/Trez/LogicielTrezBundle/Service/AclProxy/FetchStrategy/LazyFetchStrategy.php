<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LazyFetchStrategy extends AbstractFetchStrategy
{
    public function fetchChildren($entity, $child_name)
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN') === true) { // admin first (because it extends ROLE_USER)
            $children = $this->entityManager->getRepository('TrezLogicielTrezBundle:'.$child_name)->getAll($entity->getId());

            return $this->walkChildren($children);
        }
        if ($this->securityContext->isGranted('ROLE_USER') === true) {
            $userId = $this->securityContext->getToken()->getUser()->getId();
            $children = $this->entityManager->getRepository('TrezLogicielTrezBundle:'.$child_name)->getAllowed($entity->getId(), $userId);

            return $this->walkChildren($children);
        }

        throw new AccessDeniedException(); // you don't have sufficient privileges!
    }

    protected function walkChildren(array $children)
    {
        array_walk($children, function(&$item, $key) {
            $entity = $item[0];

            // create public properties for credit/debit
            $entity->credit = $item['credit'];
            $entity->debit = $item['debit'];

            $item = $entity; // replace the multidimensional array with the modified entity
        });

        return $children;
    }
}
