<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy;

use Symfony\Component\Security\Core\SecurityContext;

class EagerFetchStrategy extends AbstractFetchStrategy
{
    public function fetchChildren($entity, $child_name)
    {
        $children = $entity->{'get'.$child_name.'s'}();

        $validChildren = array();

        foreach ($children as $child) {
            $builderName = 'Trez\LogicielTrezBundle\Service\AclProxy\Builder\\'.$child_name.'Builder';
            $builder = new $builderName($this, $child);
            $builder->buildChildren();

            if ($builder->isValid() === true) {
                $builder->buildTotaux();
                $validChildren[] = $builder->getResult();
            }
        }

        return $validChildren;
    }
}