<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

class BudgetAclBuilder extends AbstractBasicAclBuilder
{
    public function getName()
    {
        return 'Budget';
    }

    public function getChildName()
    {
        return 'Categorie';
    }
}