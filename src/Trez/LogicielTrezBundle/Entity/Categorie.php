<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trez\LogicielTrezBundle\Entity\Categorie
 */
class Categorie
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $nom
     */
    private $nom;

    /**
     * @var string $commentaire
     */
    private $commentaire;

    /**
     * @var integer $cle
     */
    private $cle;

    /**
     * @var Trez\LogicielTrezBundle\Entity\Budget
     */
    private $budget;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sousCategories;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;


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
     * Set nom
     *
     * @param string $nom
     * @return Categorie
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Categorie
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    
        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set cle
     *
     * @param integer $cle
     * @return Categorie
     */
    public function setCle($cle)
    {
        $this->cle = $cle;
    
        return $this;
    }

    /**
     * Get cle
     *
     * @return integer 
     */
    public function getCle()
    {
        return $this->cle;
    }

    /**
     * Set budget
     *
     * @param Trez\LogicielTrezBundle\Entity\Budget $budget
     * @return Categorie
     */
    public function setBudget(\Trez\LogicielTrezBundle\Entity\Budget $budget = null)
    {
        $this->budget = $budget;
    
        return $this;
    }

    /**
     * Get budget
     *
     * @return Trez\LogicielTrezBundle\Entity\Budget 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /*
     * Check if parent is verrouille, thus throw an exception
     */
    public function checkVerrouille()
    {
        $this->budget->checkVerrouille();
    }
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sousCategories = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add sousCategories
     *
     * @param \Trez\LogicielTrezBundle\Entity\SousCategorie $sousCategories
     * @return Categorie
     */
    public function addSousCategorie(\Trez\LogicielTrezBundle\Entity\SousCategorie $sousCategories)
    {
        $this->sousCategories[] = $sousCategories;
    
        return $this;
    }

    /**
     * Remove sousCategories
     *
     * @param \Trez\LogicielTrezBundle\Entity\SousCategorie $sousCategories
     */
    public function removeSousCategorie(\Trez\LogicielTrezBundle\Entity\SousCategorie $sousCategories)
    {
        $this->sousCategories->removeElement($sousCategories);
    }

    /**
     * Get sousCategories
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSousCategories()
    {
        return $this->sousCategories;
    }

    /*
     * Duplicate a categorie object
     */
    public function duplicate()
    {
        $n_categorie = new Categorie();
        $n_categorie->setNom($this->nom)
                    ->setCle($this->cle)
                    ->setCommentaire($this->commentaire);
        // we skip verrouille parameter

        return $n_categorie;
    }

    // used is UserEdit form
    public function __toString()
    {
        return $this->budget->getExercice()->getEdition() . ' > ' . $this->budget->getNom() . ' > ' . $this->nom;
    }

    /**
     * Add users
     *
     * @param \Trez\LogicielTrezBundle\Entity\User $users
     * @return Categorie
     */
    public function addUser(\Trez\LogicielTrezBundle\Entity\User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param \Trez\LogicielTrezBundle\Entity\User $users
     */
    public function removeUser(\Trez\LogicielTrezBundle\Entity\User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }
}