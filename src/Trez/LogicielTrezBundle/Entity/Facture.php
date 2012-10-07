<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trez\LogicielTrezBundle\Entity\Facture
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
     * @var Trez\LogicielTrezBundle\Entity\Ligne
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
}