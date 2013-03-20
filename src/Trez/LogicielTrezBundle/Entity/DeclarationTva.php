<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DeclarationTva
 */
class DeclarationTva
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var integer
     */
    private $solde_precedent;

    /**
     * @var integer
     */
    private $solde_suivant;

    /**
     * @var string
     */
    private $feuille;

    /**
     * @var string
     */
    private $commentaire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $factures;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->factures = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
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
     * Set date
     *
     * @param \DateTime $date
     * @return DeclarationTva
     */
    public function setDate($date)
    {
        $this->date = $date;
    
        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set solde_precedent
     *
     * @param integer $soldePrecedent
     * @return DeclarationTva
     */
    public function setSoldePrecedent($soldePrecedent)
    {
        $this->solde_precedent = $soldePrecedent;
    
        return $this;
    }

    /**
     * Get solde_precedent
     *
     * @return integer 
     */
    public function getSoldePrecedent()
    {
        return $this->solde_precedent;
    }

    /**
     * Set solde_suivant
     *
     * @param integer $soldeSuivant
     * @return DeclarationTva
     */
    public function setSoldeSuivant($soldeSuivant)
    {
        $this->solde_suivant = $soldeSuivant;
    
        return $this;
    }

    /**
     * Get solde_suivant
     *
     * @return integer 
     */
    public function getSoldeSuivant()
    {
        return $this->solde_suivant;
    }

    /**
     * Set feuille
     *
     * @param string $feuille
     * @return DeclarationTva
     */
    public function setFeuille($feuille)
    {
        $this->feuille = $feuille;
    
        return $this;
    }

    /**
     * Get feuille
     *
     * @return string 
     */
    public function getFeuille()
    {
        return $this->feuille;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return DeclarationTva
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
     * Add factures
     *
     * @param \Trez\LogicielTrezBundle\Entity\Facture $factures
     * @return DeclarationTva
     */
    public function addFacture(\Trez\LogicielTrezBundle\Entity\Facture $factures)
    {
        $this->factures[] = $factures;
    
        return $this;
    }

    /**
     * Remove factures
     *
     * @param \Trez\LogicielTrezBundle\Entity\Facture $factures
     */
    public function removeFacture(\Trez\LogicielTrezBundle\Entity\Facture $factures)
    {
        $this->factures->removeElement($factures);
    }

    /**
     * Get factures
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFactures()
    {
        return $this->factures;
    }
}
