<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractCompositeBuilder extends AbstractBuilder
{
    public function getResult()
    {
        return $this->entity;
    }

    public function buildChildren()
    {
        // get children from object, using the given strategy
        $childrenArray = $this->strategy->fetchChildren($this->entity, $this->getChildName());

        // don't forget, entities need Doctrine ArrayCollections
        $childrenCollection = new ArrayCollection($childrenArray);

        $this->entity->{'set'.$this->getChildName().'s'}($childrenCollection);
    }

    public function buildTotaux() {
        $credit = 0.00;
        $debit = 0.00;

        foreach ($this->entity->{'get'.$this->getChildName().'s'}() as $child) {
            $credit += $child->credit;
            $debit += $child->debit;
        }

        $this->entity->credit = $credit; // will create such a public property
        $this->entity->debit = $debit;
    }

    /*
     * Composite nodes are valid if they have children
     * We are working with Doctrine ArrayCollection
     */
    public function isValid()
    {
        // if user is an admin, always OK
        if ($this->strategy->getSecurityContext()->isGranted('ROLE_ADMIN') === true) {
            return true;
        }

        // else children must not be empty
        return ! $this->entity->{'get'.$this->getChildName().'s'}()->isEmpty();
    }

    /*
     * Get the name of the entity we are building
     */
    abstract public function getName();

    /*
     * Get the name of the children of the current entity
     */
    abstract public function getChildName();
}
