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
     * @var string $description
     */
    private $description;

    /**
     * @var integer $cle
     */
    private $cle;

    /**
     * @var \stdClass $budget
     */
    private $budget;


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
     * Set description
     *
     * @param string $description
     * @return Categorie
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param \stdClass $budget
     * @return Categorie
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
    
        return $this;
    }

    /**
     * Get budget
     *
     * @return \stdClass 
     */
    public function getBudget()
    {
        return $this->budget;
    }
    /**
     * @var string $commmentaire
     */
    private $commmentaire;


    /**
     * Set commmentaire
     *
     * @param string $commmentaire
     * @return Categorie
     */
    public function setCommmentaire($commmentaire)
    {
        $this->commmentaire = $commmentaire;
    
        return $this;
    }

    /**
     * Get commmentaire
     *
     * @return string 
     */
    public function getCommmentaire()
    {
        return $this->commmentaire;
    }
}