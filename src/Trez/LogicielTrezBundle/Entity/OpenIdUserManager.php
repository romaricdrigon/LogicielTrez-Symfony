<?php

namespace Trez\LogicielTrezBundle\Entity;

use Fp\OpenIdBundle\Model\UserManager;

class OpenIdUserManager extends UserManager
{
    public function createUserFromIdentity($identity, array $attributes = array())
    {
        var_dump($identity, $attributes);
        var_dump($this->identityManager);
        var_dump(get_class_methods(get_class($this->identityManager)));
        die();
        return new User(); // must always return UserInterface instance or throw an exception.
    }
}