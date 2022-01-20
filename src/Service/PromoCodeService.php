<?php 

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Offer;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;

class PromoCodeService
{
    public function getPromoCodes(EntityManagerinterface $em): ?array
    {
        /* 
           Retourne la liste de tous les codes promo de la base de données
        */
        $data = $em->getRepository('App:PromoCode')->findAll();

        return $data;
    }
}
