<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trez\LogicielTrezBundle\Entity\ClasseTva
 */
class ClasseTva
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $nom
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/[\[\]]+/",
     *     match=false,
     *     message="Le nom ne peut contenir un crochet, soit [ ou ]"
     * )
     */
    private $nom;

    /**
     * @var float $taux
     * @Assert\Range(
     *      min = "0",
     *      max = "100",
     *      minMessage = "Un taux ne peut pas être inférieur à 0 !",
     *      maxMessage = "Un taux ne peut pas être de plus de 100% !"
     * )
     */
    private $taux;

    /**
     * @var boolean $actif
     */
    private $actif;

    /**
     * @var boolean
     */
    private $exclure_declaration;


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
     * @return ClasseTva
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
     * Set taux
     *
     * @param float $taux
     * @return ClasseTva
     */
    public function setTaux($taux)
    {
        $this->taux = $taux;
    
        return $this;
    }

    /**
     * Get taux
     *
     * @return float 
     */
    public function getTaux()
    {
        return $this->taux;
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return ClasseTva
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
    
        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }

    public function __toString()
    {
        $actif = $this->actif ? '' : ' #INACTIF#';
        return $this->nom . ' [' . $this->taux .']'. $actif;
    }

    /**
     * Set exclure_declaration
     *
     * @param boolean $exclureDeclaration
     * @return ClasseTva
     */
    public function setExclureDeclaration($exclureDeclaration)
    {
        $this->exclure_declaration = $exclureDeclaration;
    
        return $this;
    }

    /**
     * Get exclure_declaration
     *
     * @return boolean 
     */
    public function getExclureDeclaration()
    {
        return $this->exclure_declaration;
    }
}