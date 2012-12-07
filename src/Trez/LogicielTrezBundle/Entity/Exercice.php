<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trez\LogicielTrezBundle\Entity\Exercice
 */
class Exercice
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $edition
     */
    private $edition;

    /**
     * @var \DateTime $annee_1
     */
    private $annee_1;

    /**
     * @var \DateTime $annee_2
     */
    private $annee_2;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $budgets;


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
     * Set edition
     *
     * @param string $edition
     * @return Exercice
     */
    public function setEdition($edition)
    {
        $this->edition = $edition;
    
        return $this;
    }

    /**
     * Get edition
     *
     * @return string 
     */
    public function getEdition()
    {
        return $this->edition;
    }

    /**
     * Set annee_1
     *
     * @param \DateTime $annee1
     * @return Exercice
     */
    public function setAnnee1($annee1)
    {
        $this->annee_1 = $annee1;
    
        return $this;
    }

    /**
     * Get annee_1
     *
     * @return \DateTime 
     */
    public function getAnnee1()
    {
        return $this->annee_1;
    }

    /**
     * Set annee_2
     *
     * @param \DateTime $annee2
     * @return Exercice
     */
    public function setAnnee2($annee2)
    {
        $this->annee_2 = $annee2;
    
        return $this;
    }

    /**
     * Get annee_2
     *
     * @return \DateTime 
     */
    public function getAnnee2()
    {
        return $this->annee_2;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->budgets = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add budgets
     *
     * @param \Trez\LogicielTrezBundle\Entity\Budget $budgets
     * @return Exercice
     */
    public function addBudget(\Trez\LogicielTrezBundle\Entity\Budget $budgets)
    {
        $this->budgets[] = $budgets;
    
        return $this;
    }

    /**
     * Remove budgets
     *
     * @param \Trez\LogicielTrezBundle\Entity\Budget $budgets
     */
    public function removeBudget(\Trez\LogicielTrezBundle\Entity\Budget $budgets)
    {
        $this->budgets->removeElement($budgets);
    }

    /**
     * Get budgets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getBudgets()
    {
        return $this->budgets;
    }
}