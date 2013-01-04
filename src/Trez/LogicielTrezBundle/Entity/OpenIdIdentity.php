<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Fp\OpenIdBundle\Entity\UserIdentity as BaseUserIdentity;
use Fp\OpenIdBundle\Model\UserIdentityInterface;

/**
 * OpenIdentity
 */
class OpenIdIdentity extends BaseUserIdentity
{
    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Trez\LogicielTrezBundle\Entity\User
     */
    protected $user;

    /*
     * It inherits a identity string field,
     * and attributes text
     */


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    // no setter

    /**
     * Get user
     *
     * @return \Trez\LogicielTrezBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function __construct()
    {
        parent::__construct();
    }
}
