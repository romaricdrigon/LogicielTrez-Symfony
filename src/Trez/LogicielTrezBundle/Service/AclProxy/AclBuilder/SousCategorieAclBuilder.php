<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\AclBuilder;

class SousCategorieAclBuilder extends AbstractBasicAclBuilder
{
    public function getName()
    {
        return 'SousCategorie';
    }

    public function getChildName()
    {
        return 'Ligne';
    }
}