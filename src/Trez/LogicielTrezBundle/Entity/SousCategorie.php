<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trez\LogicielTrezBundle\Entity\SousCategorie
 */
class SousCategorie
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
     * @return SousCategorie
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
     * @return SousCategorie
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
     * @return SousCategorie
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
     * @var string $commentaire
     */
    private $commentaire;

    /**
     * @var Trez\LogicielTrezBundle\Entity\Categorie
     */
    private $categorie;


    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return SousCategorie
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
     * Set categorie
     *
     * @param Trez\LogicielTrezBundle\Entity\Categorie $categorie
     * @return SousCategorie
     */
    public function setCategorie(\Trez\LogicielTrezBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;
    
        return $this;
    }

    /**
     * Get categorie
     *
     * @return Trez\LogicielTrezBundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }
}