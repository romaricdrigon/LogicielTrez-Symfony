<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trez\LogicielTrezBundle\Entity\Ligne
 * @Assert\Callback(methods={"isUnderTotalConstraint"}, groups={"under_total"})
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
     * @var Trez\LogicielTrezBundle\Entity\SousCategorie
     */
    private $sousCategorie;

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

    /**
     * Add factures
     *
     * @param \Trez\LogicielTrezBundle\Entity\Facture $factures
     * @return Ligne
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

    /*
     * Get the sum of all factures
     */
    public function getFacturesTotal(&$credit, &$debit, $excluded_facture = -1)
    {
        $total_debit = 0.0;
        $total_credit = 0.0;

        foreach ($this->factures as $facture) {
            if ($facture->getId() === $excluded_facture) {
                continue;
            }

            // rappel : convention du banquier
            if ($facture->getTypeFacture()->getSens() === true) {
                $total_credit += $facture->getMontant();
            } else {
                $total_debit += $facture->getMontant();
            }
        }

        $credit = $total_credit;
        $debit = $total_debit;
    }

   /*
    * Get the remaining (free) money in a ligne
    */
    public function getFreeTotal(&$credit, &$debit, $exclude_facture = -1)
    {
        $this->getFacturesTotal($total_credit, $total_debit, $exclude_facture);

        $credit = $this->credit-$total_credit;
        $debit = $this->debit-$total_debit;
    }

    /*
     * Custom validator base on previous function :
     * check if factures total
     * did not exceed debit or credit
     */
    public function isUnderTotalConstraint(ExecutionContext $context)
    {
        $this->getFreeTotal($credit, $debit);

        if ($credit < 0) {
            $context->addViolationAtSubPath('credit',
                'Le total des factures dépasse le crédit de la ligne de %depassement% €',
                ['%depassement%' => $credit*-1],
                null);
        }
        if ($debit < 0) {
            $context->addViolationAtSubPath('debit',
                'Le total des factures dépasse le débit de la ligne de %depassement% €',
                ['%depassement%' => $debit*-1],
                null);
        }
    }

    /*
     * Adjust a ligne debit/credit so it's exactly the sum
     * of all factures
     */
    public function adjustTotal($sens)
    {
        $this->getFacturesTotal($credit, $debit);

        if ($sens === 'all') {
            $this->credit = $credit;
            $this->debit = $debit;
        } else if ($sens == true) {
            $this->credit = $credit;
        } else {
            $this->debit = $debit;
        }
    }

    /*
     * Check if parent is verrouille, thus throw an exception
     */
    public function checkVerrouille()
    {
        $this->sousCategorie->checkVerrouille();
    }
}