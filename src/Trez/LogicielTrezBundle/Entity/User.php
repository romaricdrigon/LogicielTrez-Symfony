<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trez\LogicielTrezBundle\Entity\User
 */
class User implements UserInterface, AdvancedUserInterface, \Serializable
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     * @Assert\MinLength(
     *     limit=5,
     *     message="Le mot de passe doit faire au moins {{ limit }} caractères."
     * )
     */
    private $password;

    /**
     * @var string $mail
     */
    private $mail;

    /**
     * @var string $type
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
        return ($this->username === $user->getUsername()) && ($this->mail === $user->getMail());
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

    /**
     * Remove categories
     *
     * @param \Trez\LogicielTrezBundle\Entity\Categorie $categories
     */
    public function removeCategorie(\Trez\LogicielTrezBundle\Entity\Categorie $categories)
    {
        $this->categories->removeElement($categories);
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
}