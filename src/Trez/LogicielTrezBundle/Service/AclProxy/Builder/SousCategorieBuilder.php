<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

class SousCategorieBuilder extends AbstractCompositeBuilder
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