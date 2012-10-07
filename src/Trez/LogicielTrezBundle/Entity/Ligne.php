<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trez\LogicielTrezBundle\Entity\Ligne
 */
class Ligne
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
     * @var float $debit
     */
    private $debit;

    /**
     * @var float $credit
     */
    private $credit;


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
     * @return Ligne
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
     * @return Ligne
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
     * @return Ligne
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
     * Set debit
     *
     * @param float $debit
     * @return Ligne
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;
    
        return $this;
    }

    /**
     * Get debit
     *
     * @return float 
     */
    public function getDebit()
    {
        return $this->debit;
    }

    /**
     * Set credit
     *
     * @param float $credit
     * @return Ligne
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
    
        return $this;
    }

    /**
     * Get credit
     *
     * @return float 
     */
    public function getCredit()
    {
        return $this->credit;
    }
    /**
     * @var string $commentaire
     */
    private $commentaire;


    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Ligne
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
     * @var Trez\LogicielTrezBundle\Entity\SousCategorie
     */
    private $sousCategorie;


    /**
     * Set sousCategorie
     *
     * @param Trez\LogicielTrezBundle\Entity\SousCategorie $sousCategorie
     * @return Ligne
     */
    public function setSousCategorie(\Trez\LogicielTrezBundle\Entity\SousCategorie $sousCategorie = null)
    {
        $this->sousCategorie = $sousCategorie;
    
        return $this;
    }

    /**
     * Get sousCategorie
     *
     * @return Trez\LogicielTrezBundle\Entity\SousCategorie 
     */
    public function getSousCategorie()
    {
        return $this->sousCategorie;
    }
}