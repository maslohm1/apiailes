<?php

namespace BP\AilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transaction
 *
 * @ORM\Table(name="Transaction")
 * @ORM\Entity
 */
class Transaction
{
    /**
     * @var string
     *
     * @ORM\Column(name="CODE TRANSACTION", type="string", length=255, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $codeTransaction = '';

    /**
     * @var string
     *
     * @ORM\Column(name="RIB", type="string", length=255, nullable=true)
     */
    private $rib;

    /**
     * @var string
     *
     * @ORM\Column(name="RIBS", type="string", length=255, nullable=false)
     */
    private $ribs;

    /**
     * @var string
     *
     * @ORM\Column(name="MONTANT", type="string", length=255, nullable=true)
     */
    private $montant;

    /**
     * @var string
     *
     * @ORM\Column(name="CODE COMMERCE", type="string", length=25, nullable=true)
     */
    private $codeCommerce;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMERCE", type="string", length=40, nullable=true)
     */
    private $commerce;

    /**
     * @var string
     *
     * @ORM\Column(name="DATE ET HEURE", type="string", length=25, nullable=true)
     */
    private $dateEtHeure;



    /**
     * Get codeTransaction
     *
     * @return string 
     */
    public function getCodeTransaction()
    {
        return $this->codeTransaction;
    }

    /**
     * Set rib
     *
     * @param string $rib
     * @return Transaction
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
     * Set ribs
     *
     * @param string $ribs
     * @return Transaction
     */
    public function setRibs($ribs)
    {
        $this->ribs = $ribs;

        return $this;
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
     * Set montant
     *
     * @param string $montant
     * @return Transaction
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return string 
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set codeCommerce
     *
     * @param string $codeCommerce
     * @return Transaction
     */
    public function setCodeCommerce($codeCommerce)
    {
        $this->codeCommerce = $codeCommerce;

        return $this;
    }

    /**
     * Get codeCommerce
     *
     * @return string 
     */
    public function getCodeCommerce()
    {
        return $this->codeCommerce;
    }

    /**
     * Set commerce
     *
     * @param string $commerce
     * @return Transacion
     */
    public function setCommerce($commerce)
    {
        $this->commerce = $commerce;

        return $this;
    }

    /**
     * Get commerce
     *
     * @return string 
     */
    public function getCommerce()
    {
        return $this->commerce;
    }

    /**
     * Set dateEtHeure
     *
     * @param string $dateEtHeure
     * @return Transaction
     */
    public function setDateEtHeure($dateEtHeure)
    {
        $this->dateEtHeure = $dateEtHeure;

        return $this;
    }

    /**
     * Get dateEtHeure
     *
     * @return string 
     */
    public function getDateEtHeure()
    {
        return $this->dateEtHeure;
    }
}
