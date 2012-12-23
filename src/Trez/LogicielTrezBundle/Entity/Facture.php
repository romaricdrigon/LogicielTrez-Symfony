<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trez\LogicielTrezBundle\Entity\Facture
 *  @Assert\Callback(methods={"isUnderTotal"}, groups={"under_total"})
 *  @Assert\Callback(methods={"isNotNull"})
 *  @Assert\Callback(methods={"isTvasCorrect"})
 */
class Facture
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var integer $numero
     */
    private $numero;

    /**
     * @var string $objet
     */
    private $objet;

    /**
     * @var float $montant
     */
    private $montant;

    /**
     * @var \DateTime $date
     */
    private $date;

    /**
     * @var \DateTime $date_paiement
     */
    private $date_paiement;

    /**
     * @var string $commentaire
     */
    private $commentaire;

    /**
     * @var string $ref_paiement
     */
    private $ref_paiement;

    /**
     * @var Trez\LogicielTrezBundle\Entity\Ligne
     * @Assert\Valid
     */
    private $ligne;

    /**
     * @var Trez\LogicielTrezBundle\Entity\Tiers
     */
    private $tiers;

    /**
     * @var Trez\LogicielTrezBundle\Entity\MethodePaiement
     */
    private $methodePaiement;

    /**
     * @var Trez\LogicielTrezBundle\Entity\TypeFacture
     */
    private $typeFacture;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $tvas;


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
     * Set numero
     *
     * @param integer $numero
     * @return Facture
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    
        return $this;
    }

    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set objet
     *
     * @param string $objet
     * @return Facture
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;
    
        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set montant
     *
     * @param float $montant
     * @return Facture
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;
    
        return $this;
    }

    /**
     * Get montant
     *
     * @return float 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Facture
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
     * Set date_paiement
     *
     * @param \DateTime $datePaiement
     * @return Facture
     */
    public function setDatePaiement($datePaiement)
    {
        $this->date_paiement = $datePaiement;
    
        return $this;
    }

    /**
     * Get date_paiement
     *
     * @return \DateTime 
     */
    public function getDatePaiement()
    {
        return $this->date_paiement;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Facture
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
     * Set ref_paiement
     *
     * @param string $refPaiement
     * @return Facture
     */
    public function setRefPaiement($refPaiement)
    {
        $this->ref_paiement = $refPaiement;
    
        return $this;
    }

    /**
     * Get ref_paiement
     *
     * @return string 
     */
    public function getRefPaiement()
    {
        return $this->ref_paiement;
    }

    /**
     * Set ligne
     *
     * @param Trez\LogicielTrezBundle\Entity\Ligne $ligne
     * @return Facture
     */
    public function setLigne(\Trez\LogicielTrezBundle\Entity\Ligne $ligne = null)
    {
        $this->ligne = $ligne;
    
        return $this;
    }

    /**
     * Get ligne
     *
     * @return Trez\LogicielTrezBundle\Entity\Ligne 
     */
    public function getLigne()
    {
        return $this->ligne;
    }

    /**
     * Set tiers
     *
     * @param Trez\LogicielTrezBundle\Entity\Tiers $tiers
     * @return Facture
     */
    public function setTiers(\Trez\LogicielTrezBundle\Entity\Tiers $tiers = null)
    {
        $this->tiers = $tiers;
    
        return $this;
    }

    /**
     * Get tiers
     *
     * @return Trez\LogicielTrezBundle\Entity\Tiers 
     */
    public function getTiers()
    {
        return $this->tiers;
    }

    /**
     * Set methodePaiement
     *
     * @param Trez\LogicielTrezBundle\Entity\MethodePaiement $methodePaiement
     * @return Facture
     */
    public function setMethodePaiement(\Trez\LogicielTrezBundle\Entity\MethodePaiement $methodePaiement = null)
    {
        $this->methodePaiement = $methodePaiement;
    
        return $this;
    }

    /**
     * Get methodePaiement
     *
     * @return Trez\LogicielTrezBundle\Entity\MethodePaiement 
     */
    public function getMethodePaiement()
    {
        return $this->methodePaiement;
    }

    /**
     * Set typeFacture
     *
     * @param Trez\LogicielTrezBundle\Entity\TypeFacture $typeFacture
     * @return Facture
     */
    public function setTypeFacture(\Trez\LogicielTrezBundle\Entity\TypeFacture $typeFacture = null)
    {
        $this->typeFacture = $typeFacture;
    
        return $this;
    }

    /**
     * Get typeFacture
     *
     * @return Trez\LogicielTrezBundle\Entity\TypeFacture 
     */
    public function getTypeFacture()
    {
        return $this->typeFacture;
    }

    /*
     * Check if we don't exceed ligne total (debit or credit)
     */
    public function isUnderTotal(ExecutionContext $context)
    {
        $this->ligne->getFreeTotal($credit, $debit, $this->id);

        $depassement = ($this->typeFacture->getSens() === true) ? $this->montant-$credit : $this->montant-$debit;

        if ($depassement > 0) {
            $context->addViolationAtSubPath('montant',
                'Cette facture dépasse du total de la ligne de %montant% €',
                array('%montant%' => $depassement),
                null);
        }
    }

    /*
     * Check if montant is not null
     */
    public function isNotNull(ExecutionContext $context)
    {
        if ($this->montant === 0.0) {
            $context->addViolationAtSubPath('montant', 'Une facture ne peut pas avoir un montant nul', array(), null);
        }
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tvas = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add tvas
     *
     * @param \Trez\LogicielTrezBundle\Entity\Tva $tvas
     * @return Facture
     */
    public function addTva(\Trez\LogicielTrezBundle\Entity\Tva $tvas)
    {
        $tvas->setFacture($this); // added because we're the inverse side

        $this->tvas[] = $tvas;
    
        return $this;
    }

    /**
     * Remove tvas
     *
     * @param \Trez\LogicielTrezBundle\Entity\Tva $tvas
     */
    public function removeTva(\Trez\LogicielTrezBundle\Entity\Tva $tvas)
    {
        $this->tvas->removeElement($tvas);
    }

    /**
     * Get tvas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTvas()
    {
        return $this->tvas;
    }

    /*
     * Check if parent is verrouille, thus throw an exception
     */
    public function checkVerrouille()
    {
        $this->ligne->checkVerrouille();
    }

    /*
     * Get the montant TTC, including TVA
     */
    public function getMontantTtc()
    {
        $montant_ttc = $this->montant;

        foreach ($this->tvas as $tva) {
            $montant_ttc += $tva->getMontantTva();
        }

        return $montant_ttc;
    }

    /*
     * Check if not TVA is missing
     */
    public function isTvasCorrect(ExecutionContext $context)
    {
        $montant_ht = 0;

        foreach ($this->tvas as $tva) {
            $montant_ht += $tva->getMontantHt();
        }

        if ($this->montant != $montant_ht) {
            $context->addViolationAtSubPath('tvas', 'La somme des montants HT doit être égale au montant de la facture');
        }
    }
}