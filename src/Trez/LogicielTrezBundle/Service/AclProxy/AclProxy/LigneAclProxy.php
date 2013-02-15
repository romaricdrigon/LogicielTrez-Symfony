<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclProxy;

use Trez\LogicielTrezBundle\Entity\Ligne;

/*
 * Not used at the moment (useless, transparent)
 */
class LigneAclProxy extends Ligne implements AclProxyInterface
{
    protected $proxied;

    public function __construct(Ligne $entity)
    {
        $this->proxied = $entity;
    }

    public function getDebit()
    {
        return $this->proxied->getDebit();
    }

    public function getCredit()
    {
        return $this->proxied->getCredit();
    }

    public function getProxied()
    {
        return $this->proxied;
    }
}