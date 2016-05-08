<?php

namespace BP\AilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Membres
 *
 * @ORM\Table(name="Membres", uniqueConstraints={@ORM\UniqueConstraint(name="RIB", columns={"RIB"})})
 * @ORM\Entity
 */
class Membres
{
    /**
     * @var string
     *
     * @ORM\Column(name="TITRE", type="string", length=4, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="INTITULE", type="string", length=30, nullable=true)
     */
    private $intitule;

    /**
     * @var string
     *
     * @ORM\Column(name="SIGLE", type="string", length=30, nullable=true)
     */
    private $sigle;

    /**
     * @var integer
     *
     * @ORM\Column(name="BANQUE", type="integer", nullable=true)
     */
    private $banque;

    /**
     * @var integer
     *
     * @ORM\Column(name="AGENCE", type="integer", nullable=true)
     */
    private $agence;

    /**
     * @var string
     *
     * @ORM\Column(name="RIBS", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $ribs;

    /**
     * @var string
     *
     * @ORM\Column(name="DT_CREATION", type="string", length=25, nullable=true)
     */
    private $dtCreation;

    /**
     * @var string
     *
     * @ORM\Column(name="DT_NAIS", type="string", length=10, nullable=true)
     */
    private $dtNais;

    /**
     * @var string
     *
     * @ORM\Column(name="GSM", type="string", length=15, nullable=true)
     */
    private $gsm;

    /**
     * @var string
     *
     * @ORM\Column(name="MAIL", type="string", length=41, nullable=true)
     */
    private $mail;

    /**
     * @var string
     *
     * @ORM\Column(name="radical", type="string", length=50, nullable=true)
     */
    private $radical;
    


    /**
     * Set titre
     *
     * @param string $titre
     * @return Membres
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set intitule
     *
     * @param string $intitule
     * @return Membres
     */
    public function setIntitule($intitule)
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * Get intitule
     *
     * @return string 
     */
    public function getIntitule()
    {
        return $this->intitule;
    }

    /**
     * Set sigle
     *
     * @param string $sigle
     * @return Membres
     */
    public function setSigle($sigle)
    {
        $this->sigle = $sigle;

        return $this;
    }

    /**
     * Get sigle
     *
     * @return string 
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * Set banque
     *
     * @param integer $banque
     * @return Membres
     */
    public function setBanque($banque)
    {
        $this->banque = $banque;

        return $this;
    }

    /**
     * Get banque
     *
     * @return integer 
     */
    public function getBanque()
    {
        return $this->banque;
    }

    /**
     * Set agence
     *
     * @param integer $agence
     * @return Membres
     */
    public function setAgence($agence)
    {
        $this->agence = $agence;

        return $this;
    }

    /**
     * Get agence
     *
     * @return integer 
     */
    public function getAgence()
    {
        return $this->agence;
    }

    /**
     * Get ribs
     *
     * @return string 
     */
    public function getRibs()
    {
        return $this->ribs;
    }

    /**
     * Set dtCreation
     *
     * @param string $dtCreation
     * @return Membres
     */
    public function setDtCreation($dtCreation)
    {
        $this->dtCreation = $dtCreation;

        return $this;
    }

    /**
     * Get dtCreation
     *
     * @return string 
     */
    public function getDtCreation()
    {
        return $this->dtCreation;
    }

    /**
     * Set dtNais
     *
     * @param string $dtNais
     * @return Membres
     */
    public function setDtNais($dtNais)
    {
        $this->dtNais = $dtNais;

        return $this;
    }

    /**
     * Get dtNais
     *
     * @return string 
     */
    public function getDtNais()
    {
        return $this->dtNais;
    }

    /**
     * Set gsm
     *
     * @param string $gsm
     * @return Membres
     */
    public function setGsm($gsm)
    {
        $this->gsm = $gsm;

        return $this;
    }

    /**
     * Get gsm
     *
     * @return string 
     */
    public function getGsm()
    {
        return $this->gsm;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Membres
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
     * Set radical
     *
     * @param string $radical
     * @return Membres
     */
    public function setRadical($radical)
    {
        $this->radical = $radical;

        return $this;
    }

    /**
     * Get radical
     *
     * @return string 
     */
    public function getRadical()
    {
        return $this->radical;
    }
}
