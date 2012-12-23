<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trez\LogicielTrezBundle\Entity\Tva
 */
class Tva
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var float $montant_ht
     * @Assert\NotNull()
     */
    private $montant_ht;

    /**
     * @var float $montant_tva
     * @Assert\NotBlank()
     */
    private $montant_tva;

    /**
     * @var Trez\LogicielTrezBundle\Entity\Facture
     */
    private $facture;

    /**
     * @var Trez\LogicielTrezBundle\Entity\ClasseTva
     */
    private $classeTva;


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
     * Set montant_ht
     *
     * @param float $montantHt
     * @return Tva
     */
    public function setMontantHt($montantHt)
    {
        $this->montant_ht = $montantHt;
    
        return $this;
    }

    /**
     * Get montant_ht
     *
     * @return float 
     */
    public function getMontantHt()
    {
        return $this->montant_ht;
    }

    /**
     * Set montant_tva
     *
     * @param float $montantTva
     * @return Tva
     */
    public function setMontantTva($montantTva)
    {
        $this->montant_tva = $montantTva;
    
        return $this;
    }

    /**
     * Get montant_tva
     *
     * @return float 
     */
    public function getMontantTva()
    {
        return $this->montant_tva;
    }

    /**
     * Set facture
     *
     * @param Trez\LogicielTrezBundle\Entity\Facture $facture
     * @return Tva
     */
    public function setFacture(\Trez\LogicielTrezBundle\Entity\Facture $facture = null)
    {
        $this->facture = $facture;
    
        return $this;
    }

    /**
     * Get facture
     *
     * @return Trez\LogicielTrezBundle\Entity\Facture 
     */
    public function getFacture()
    {
        return $this->facture;
    }

    /**
     * Set classeTva
     *
     * @param Trez\LogicielTrezBundle\Entity\ClasseTva $classeTva
     * @return Tva
     */
    public function setClasseTva(\Trez\LogicielTrezBundle\Entity\ClasseTva $classeTva = null)
    {
        $this->classeTva = $classeTva;
    
        return $this;
    }

    /**
     * Get classeTva
     *
     * @return Trez\LogicielTrezBundle\Entity\ClasseTva 
     */
    public function getClasseTva()
    {
        return $this->classeTva;
    }

    /*
     * Check if parent is verrouille, thus throw an exception
     */
    public function checkVerrouille()
    {
        $this->facture->checkVerrouille();
    }
}