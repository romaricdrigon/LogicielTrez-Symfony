<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

class CategorieAclBuilder extends AbstractBasicAclBuilder
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