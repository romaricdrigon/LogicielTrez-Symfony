<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

use Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy\FetchStrategyInterface;

abstract class AbstractBuilder implements BuilderInterface
{
    protected $entity;
    protected $strategy;

    public function __construct(FetchStrategyInterface $strategy, $entity)
    {
        $this->strategy = $strategy;
        $this->entity = $entity;
    }

    /*
     * FetchChildren will fetch children collection (all children, not filtered)
     */
    abstract public function buildChildren();

    /*
     * Build may calculate/set needed parameters (such as credit & debit)
     */
    abstract public function buildTotaux();

    /*
     * Is this node valid given the security context?
     */
    abstract public function isValid();

    /*
     * Return the entity
     */
    public function getResult()
    {
        return $this->entity;
    }
}
