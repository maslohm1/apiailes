<?php

namespace BP\AilesBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * GeolocRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GeolocRepository extends EntityRepository
{
    
    public function getPOIS($category,$latitude,$longitude){
        
        $sql = "SELECT * FROM `Geoloc` WHERE category='$category' order by `distance terrestre`(Longitude,Latitude,$longitude,$latitude) LIMIT 0,30";
        
        return $this->getEntityManager()->getConnection()->executeQuery($sql)->fetchAll();
        
    }
    
}
