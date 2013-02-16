<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class LigneFetchStrategy extends AbstractFetchStrategy
{
    public function fetchChildren($entity, $child_name)
    {
        if ($this->securityContext->isGranted('ROLE_ADMIN') === true) {
            return $this->entityManager->getRepository('TrezLogicielTrezBundle:Ligne')->findBy(
                array('sousCategorie' => $entity),
                array('cle' => 'ASC')
            );
        }
        if ($this->securityContext->isGranted('ROLE_USER') === true) {
            return $this->entityManager->getRepository('TrezLogicielTrezBundle:Ligne')->getAllowed($entity->getId(), $this->securityContext->getToken()->getUser()->getId());
        }

        throw new AccessDeniedException(); // you don't have sufficient privileges!
    }
}