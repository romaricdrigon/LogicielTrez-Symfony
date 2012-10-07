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
}