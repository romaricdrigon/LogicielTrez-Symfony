<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

class CategorieAclBuilder extends BasicAclBuilder
{
    public function getName()
    {
        return 'Categorie';
    }

    public function getChildName()
    {
        return 'SousCategorie';
    }
}