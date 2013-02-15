<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclProxy;

use Trez\LogicielTrezBundle\Entity\SousCategorie;

class SousCategorieAclProxy extends SousCategorie implements ParentInterface, TotauxInterface, AclProxyInterface
{
    protected $proxied;

    public function __construct(SousCategorie $entity)
    {
        $this->proxied = $entity;
    }

    public function setChildren(array $children)
    {
        $this->proxied->lignes = $children;
    }

    public function getChildren()
    {
        return $this->proxied->lignes;
    }

    public function setDebit($debit)
    {
        $this->proxied->debit = $debit; // will such a public property
    }

    public function setCredit($credit)
    {
        $this->proxied->credit = $credit; // will such a public property
    }

    public function getDebit()
    {
        return $this->proxied->debit;
    }

    public function getCredit()
    {
        return $this->proxied->credit;
    }

    public function getProxied()
    {
        return $this->proxied;
    }
}