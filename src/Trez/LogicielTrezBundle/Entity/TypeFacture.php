<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trez\LogicielTrezBundle\Entity\TypeFacture
 */
class TypeFacture
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $abr
     */
    private $abr;

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
     * Set abr
     *
     * @param string $abr
     * @return TypeFacture
     */
    public function setAbr($abr)
    {
        $this->abr = $abr;
    
        return $this;
    }

    /**
     * Get abr
     *
     * @return string 
     */
    public function getAbr()
    {
        return $this->abr;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return TypeFacture
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
     * @var boolean $sens
     */
    private $sens;


    /**
     * Set sens
     *
     * @param boolean $sens
     * @return TypeFacture
     */
    public function setSens($sens)
    {
        $this->sens = $sens;
    
        return $this;
    }

    /**
     * Get sens
     *
     * @return boolean 
     */
    public function getSens()
    {
        return $this->sens;
    }
}