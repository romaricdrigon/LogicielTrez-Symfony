<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

class BudgetAclBuilder extends AbstractCompositeBuilder
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