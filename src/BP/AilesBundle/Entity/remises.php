<?php

namespace BP\AilesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * remises
 *
 * @ORM\Table(name="remises")
 */
class remises
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="min_montant", type="string", length=255, unique=true)
     */
    private $min_montant;

    /**
     * @var string
     *
     * @ORM\Column(name="max_montant", type="string", length=255)
     */
    private $max_montant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="remise", type="string", length=255)
     */
    private $remise;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="id_partenaire", type="integer")
     */
    private $id_partenaire;


    
}
