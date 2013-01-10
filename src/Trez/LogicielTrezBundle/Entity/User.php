<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\ExecutionContext;

/**
 * Trez\LogicielTrezBundle\Entity\User
 * @Assert\Callback(methods={"hasOneLoginType"})
 * @Assert\Callback(methods={"checkPasswordLength"})
 * @UniqueEntity(fields={"username"}, message="Ce nom d'utilisateur est déjà pris (à la casse près)")
 * @UniqueEntity(fields={"mail"}, message="Cette adresse e-mail est déjà utilisée")
 * Note: UniqueEntity is case-insensitive
 */
class User implements UserInterface, AdvancedUserInterface, \Serializable
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $username
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $mail
     * @Assert\Email(
     *     message = "Une adresse e-mail valide est requise"
     * )
     */
    private $mail;

    /**
     * @var string $type
     * @Assert\Choice(choices = {"ROLE_ADMIN", "ROLE_USER", "DISABLED"}, message = "Le type doit être parmi 'ROLE_ADMIN', 'ROLE_USER' et 'DISABLED'")
     */
    private $type;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $categories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $open_id_identities;

    /**
     * @var boolean
     */
    private $can_openid;

    /**
     * @var boolean
     */
    private $can_credentials;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;
    
        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return User
     */
    public function setType($type)
    {
        $this->type = $type;
    
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return User
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    
        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    // set salt at init
    public function __construct()
    {
        $this->salt = md5(uniqid(null, true));
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        if ($this->type === null) {
            return array();
        }

        return array($this->type);
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }

    /**
     * @inheritDoc
     */
    public function equals(UserInterface $user)
    {
        $step1 = true;
        if (method_exists($user, 'getId')) {
            $step1 = ($this->id === $user->getId());
        }

        return $step1 && ($this->username === $user->getUsername()) && ($this->mail === $user->getMail());
    }

    /*
     * AdvancedUserInteface add this methods
     */
    public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        if ($this->type === 'DISABLED') {
            return false;
        }

        return true;
    }

    /**
     * Add categories
     *
     * @param \Trez\LogicielTrezBundle\Entity\Categorie $categories
     * @return User
     */
    public function addCategorie(\Trez\LogicielTrezBundle\Entity\Categorie $categories)
    {
        $this->categories[] = $categories;
    
        return $this;
    }
    // alias tio prevent
    public function addCategory(\Trez\LogicielTrezBundle\Entity\Categorie $categories)
    {
        return $this->addCategorie($categories);
    }

    /**
     * Remove categories
     *
     * @param \Trez\LogicielTrezBundle\Entity\Categorie $categories
     */
    public function removeCategorie(\Trez\LogicielTrezBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
    }
    public function removeCategory(\Trez\LogicielTrezBundle\Entity\Categorie $categories)
    {
        $this->removeCategorie($categories);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCategories()
    {
        return $this->categories;
    }

    public function isCategorieAllowed($categorie)
    {
        return $this->categories->contains($categorie);
    }

    /**
     * Add open_id_identities
     *
     * @param \Trez\LogicielTrezBundle\Entity\OpenIdIdentity $openIdIdentities
     * @return User
     */
    public function addOpenIdIdentitie(\Trez\LogicielTrezBundle\Entity\OpenIdIdentity $openIdIdentities)
    {
        $this->open_id_identities[] = $openIdIdentities;
    
        return $this;
    }

    /**
     * Remove open_id_identities
     *
     * @param \Trez\LogicielTrezBundle\Entity\OpenIdIdentity $openIdIdentities
     */
    public function removeOpenIdIdentitie(\Trez\LogicielTrezBundle\Entity\OpenIdIdentity $openIdIdentities)
    {
        $this->open_id_identities->removeElement($openIdIdentities);
    }

    /**
     * Get open_id_identities
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOpenIdIdentities()
    {
        return $this->open_id_identities;
    }

    /**
     * Set can_openid
     *
     * @param boolean $canOpenid
     * @return User
     */
    public function setCanOpenid($canOpenid)
    {
        $this->can_openid = $canOpenid;
    
        return $this;
    }

    /**
     * Get can_openid
     *
     * @return boolean 
     */
    public function getCanOpenid()
    {
        return $this->can_openid;
    }

    /**
     * Set can_credentials
     *
     * @param boolean $canCredentials
     * @return User
     */
    public function setCanCredentials($canCredentials)
    {
        $this->can_credentials = $canCredentials;
    
        return $this;
    }

    /**
     * Get can_credentials
     *
     * @return boolean 
     */
    public function getCanCredentials()
    {
        return $this->can_credentials;
    }

    /*
     * Check if User can logged (at least) in one way
     */
    public function hasOneLoginType(ExecutionContext $context)
    {
        if ($this->can_openid === false && $this->can_credentials === false && $this->type !== 'DISABLED') {
            $context->addViolationAtSubPath('can_openid', "Un utilisateur actif doit pouvoir se connecter au moins d'une manière");
            $context->addViolationAtSubPath('can_credentials', "Un utilisateur actif doit pouvoir se connecter au moins d'une manière");
        }
    }

    /*
     * Check password length:
     *  - if User can connect using credentials > 6 chars
     *  - else can be blank
     */
    public function checkPasswordLength(ExecutionContext $context)
    {
        if ($this->can_credentials === true && mb_strlen($this->password, 'UTF-8') < 6) {
            $context->addViolationAtSubPath('password', "Le mot de passe doit faire au minimum 6 caractères");
        }
    }
}