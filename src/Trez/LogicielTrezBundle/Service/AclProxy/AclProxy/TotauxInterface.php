<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclProxy;

interface TotauxInterface
{
    public function setDebit($debit);

    public function setCredit($credit);

    public function getDebit();

    public function getCredit();
}