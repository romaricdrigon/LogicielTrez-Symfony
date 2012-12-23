<?php

namespace Trez\LogicielTrezBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trez\LogicielTrezBundle\Entity\Tiers
 * @UniqueEntity("nom")
 */
class Tiers
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
     * @var string $telephone
     */
    private $telephone;

    /**
     * @var string $mail
     * @Assert\Email(
     *     message = "Une adresse e-mail valide est requise"
     * )
     */
    private $mail;

    /**
     * @var string $fax
     */
    private $fax;

    /**
     * @var string $adresse
     */
    private $adresse;

    /**
     * @var string $responsable
     */
    private $responsable;

    /**
     * @var string $rib
     */
    private $rib;

    /**
     * @var string $ordre_cheque
     */
    private $ordre_cheque;

    /**
     * @var string $commentaire
     */
    private $commentaire;


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
     * @return Tiers
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
     * Set telephone
     *
     * @param string $telephone
     * @return Tiers
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    
        return $this;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Tiers
     */
    public function setMail($mail)
    {
        $this->mail = $mail;
    
        return $this;
    }

    /**
     * Get mail
     *
     * @return string 
     */
    public function getMail()
    {
        return $this->mail;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Tiers
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
    
        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return Tiers
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set responsable
     *
     * @param string $responsable
     * @return Tiers
     */
    public function setResponsable($responsable)
    {
        $this->responsable = $responsable;
    
        return $this;
    }

    /**
     * Get responsable
     *
     * @return string 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }

    /**
     * Set rib
     *
     * @param string $rib
     * @return Tiers
     */
    public function setRib($rib)
    {
        $this->rib = $rib;
    
        return $this;
    }

    /**
     * Get rib
     *
     * @return string 
     */
    public function getRib()
    {
        return $this->rib;
    }

    /**
     * Set ordre_cheque
     *
     * @param string $ordreCheque
     * @return Tiers
     */
    public function setOrdreCheque($ordreCheque)
    {
        $this->ordre_cheque = $ordreCheque;
    
        return $this;
    }

    /**
     * Get ordre_cheque
     *
     * @return string 
     */
    public function getOrdreCheque()
    {
        return $this->ordre_cheque;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return Tiers
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
}