<?php

namespace Trez\LogicielTrezBundle\Service\AclProxy\Builder;

class CategorieBuilder extends AbstractCompositeBuilder
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
