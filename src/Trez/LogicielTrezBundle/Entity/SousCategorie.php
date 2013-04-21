<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var string $description
     */
    private $description;

    /**
     * @var integer $cle
     * @Assert\NotBlank()
     */
    private $cle;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $lignes;


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

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lignes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Add lignes
     *
     * @param \Trez\LogicielTrezBundle\Entity\Ligne $lignes
     * @return SousCategorie
     */
    public function addLigne(\Trez\LogicielTrezBundle\Entity\Ligne $lignes)
    {
        $this->lignes[] = $lignes;
    
        return $this;
    }

    /**
     * Remove lignes
     *
     * @param \Trez\LogicielTrezBundle\Entity\Ligne $lignes
     */
    public function removeLigne(\Trez\LogicielTrezBundle\Entity\Ligne $lignes)
    {
        $this->lignes->removeElement($lignes);
    }

    /**
     * Get lignes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getLignes()
    {
        return $this->lignes;
    }

    /**
     * Set Lignes
     *
     * @param \Doctrine\Common\Collections\Collection
     * @return \Trez\LogicielTrezBundle\Entity\SousCategorie
     */
    public function setLignes(\Doctrine\Common\Collections\Collection $collection)
    {
        $this->lignes = $collection;

        return $this;
    }

    /*
     * Check if parent is verrouille, thus throw an exception
     */
    public function checkVerrouille()
    {
        $this->categorie->checkVerrouille();
    }

    /*
     * Duplicate a sous-categorie object
     */
    public function duplicate()
    {
        $n_sous_categorie = new SousCategorie();
        $n_sous_categorie->setCle($this->cle)
                        ->setCommentaire($this->commentaire)
                        ->setNom($this->nom)
                        ->setDescription($this->description);

        return $n_sous_categorie;
    }

    /*
     * Some getter to simulate properties
     */
    /*public function getDebit()
    {
        $debit = 0.00;

        foreach ($this->lignes as $ligne) {
            $debit += $ligne->getDebit();
        }

        return $debit;
    }
    public function getCredit()
    {
        $credit = 0.00;

        foreach ($this->lignes as $ligne) {
            $credit += $ligne->getCredit();
        }

        return $credit;
    }*/
}
