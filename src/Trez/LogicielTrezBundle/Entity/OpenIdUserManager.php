<?php

namespace Trez\LogicielTrezBundle\Entity;

use Fp\OpenIdBundle\Model\UserManager;
use Fp\OpenIdBundle\Model\IdentityManagerInterface;
use Doctrine\ORM\EntityManager;
use Trez\LogicielTrezBundle\Entity\OpenIdIdentity;

class OpenIdUserManager extends UserManager
{
    private $entityManager;

    public function __construct(IdentityManagerInterface $identityManager, EntityManager $entityManager)
    {
        parent::__construct($identityManager);

        $this->entityManager = $entityManager;
    }

    public function createUserFromIdentity($identity, array $attributes = array())
    {
        if (isset($attributes['contact/email']) === false) {
            throw new \Exception('Le provider ne nous a pas fourni votre adresse e-mail !');
        }
        $user = $this->entityManager->getRepository('TrezLogicielTrezBundle:User')->findOneBy(array(
            'mail' => $attributes['contact/email']
        ));

        if ($user === null) {
            throw new \Exception('Cet utilisateur ne semble pas être autorisé');
        }

        // we create an OpenIdIdentity using this user
        $openIdIdentity = new OpenIdIdentity();
        $openIdIdentity->setIdentity($identity);
        $openIdIdentity->setAttributes($attributes);
        $openIdIdentity->setUser($user);

        $this->entityManager->persist($openIdIdentity);
        $this->entityManager->flush();

        return $user; // must always return UserInterface instance or throw an exception.
    }
}