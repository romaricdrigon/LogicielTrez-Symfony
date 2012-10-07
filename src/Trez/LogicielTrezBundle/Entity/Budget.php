<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     */
    private $nom;


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
}