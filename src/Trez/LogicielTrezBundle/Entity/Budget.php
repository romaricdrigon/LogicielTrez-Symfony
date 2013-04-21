<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Trez\LogicielTrezBundle\Exception\LockedException;

/**
 * Trez\LogicielTrezBundle\Entity\Budget
 */
class Budget
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $nom
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $categories;

    /**
     * @var boolean
     */
    private $verrouille;


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
     * @return Budget
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
     * @var Trez\LogicielTrezBundle\Entity\Exercice
     */
    private $exercice;


    /**
     * Set exercice
     *
     * @param Trez\LogicielTrezBundle\Entity\Exercice $exercice
     * @return Budget
     */
    public function setExercice(\Trez\LogicielTrezBundle\Entity\Exercice $exercice = null)
    {
        $this->exercice = $exercice;
    
        return $this;
    }

    /**
     * Get exercice
     *
     * @return Trez\LogicielTrezBundle\Entity\Exercice 
     */
    public function getExercice()
    {
        return $this->exercice;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->categories = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add categories
     *
     * @param \Trez\LogicielTrezBundle\Entity\Categorie $categories
     * @return Budget
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

    /**
     * Set categories
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return \Trez\LogicielTrezBundle\Entity\Budget
     * @return Budget
     */
    public function setCategories(\Doctrine\Common\Collections\Collection $collection)
    {
        $this->categories = $collection;

        return $this;
    }

    /**
     * Set verrouille
     *
     * @param boolean $verrouille
     * @return Budget
     */
    public function setVerrouille($verrouille)
    {
        $this->verrouille = $verrouille;
    
        return $this;
    }

    /**
     * Get verrouille
     *
     * @return boolean 
     */
    public function getVerrouille()
    {
        return $this->verrouille;
    }

    /*
     * Check and block flush if exercice is verrouille (locked)
     * Either the budget or the exercice may be locked
     */
    public function checkExerciceVerrouille()
    {
        $this->exercice->checkVerrouille();
    }
    public function checkVerrouille()
    {
        if ($this->verrouille === true) {
            throw new LockedException();
        }

        $this->exercice->checkVerrouille();
    }

    /*
     * Duplicate a budget object
     */
    public function duplicate()
    {
        $n_budget = new Budget();
        $n_budget->setNom('Copie de ' . $this->nom)
                    ->setExercice($this->exercice)
                    ->setVerrouille(false);
        // verrouille has to be false

        return $n_budget;
    }

    public function getFullNom()
    {
        return $this->exercice->getEdition() . ' - ' . $this->nom;
    }

    /*
     * Some getter to simulate properties
     */
    /*public function getDebit()
    {
        $debit = 0.00;

        foreach ($this->categories as $categorie) {
            $debit += $categorie->getDebit();
        }

        return $debit;
    }
    public function getCredit()
    {
        $credit = 0.00;

        foreach ($this->categories as $categorie) {
            $credit += $categorie->getCredit();
        }

        return $credit;
    }*/
    public function getNotEmptyCategories()
    {
        $array = array();

        foreach ($this->categories as $categorie) {
            if (sizeof($categorie->getNotEmptySousCategories()) > 0) {
                $array[] = $categorie;
            }
        }

        usort($array, function($a, $b) {
            return $a->getCle() - $b->getCle();
        });

        return $array;
    }
}
