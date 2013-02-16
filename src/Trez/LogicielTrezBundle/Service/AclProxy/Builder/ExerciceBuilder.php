<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

use Doctrine\Common\Collections\ArrayCollection;

class ExerciceBuilder extends AbstractBuilder
{
    public function buildChildren()
    {
        // get children from object, using the given strategy
        $childrenArray = $this->strategy->fetchChildren($this->entity, 'Budget');

        // don't forget, entities need Doctrine ArrayCollections
        $childrenCollection = new ArrayCollection($childrenArray);

        $this->entity->setBudgets($childrenCollection);
    }

    public function buildTotaux() {
        // an exercice doesn't have totaux at the moment
    }

    /*
     * Composite nodes are valid if they have children
     * We are working with Doctrine ArrayCollection
     */
    public function isValid()
    {
        return ! $this->entity->getBudgets()->isEmpty();
    }
}