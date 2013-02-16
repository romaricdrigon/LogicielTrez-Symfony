<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

use Trez\LogicielTrezBundle\Service\AclProxy\FetchStrategy\FetchStrategyInterface;

interface BuilderInterface
{
    public function __construct(FetchStrategyInterface $strategy, $entity);

    public function getResult();

    public function buildChildren();

    public function buildTotaux();

    public function isValid();
}