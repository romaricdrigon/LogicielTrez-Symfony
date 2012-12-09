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
     * @param \Trez\LogicielTrezBundle\Entity\sousCategorie $sousCategories
     * @return Categorie
     */
    public function addSousCategorie(\Trez\LogicielTrezBundle\Entity\sousCategorie $sousCategories)
    {
        $this->sousCategories[] = $sousCategories;
    
        return $this;
    }

    /**
     * Remove sousCategories
     *
     * @param \Trez\LogicielTrezBundle\Entity\sousCategorie $sousCategories
     */
    public function removeSousCategorie(\Trez\LogicielTrezBundle\Entity\sousCategorie $sousCategories)
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
}