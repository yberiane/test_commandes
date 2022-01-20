<?php 

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use App\Entity\Offer;
use App\Service\ApiService;
use Doctrine\ORM\EntityManagerInterface;

class OfferService
{
    public function getOffers(entityManagerInterface $em): ?array
    {
        /* 
           Retourne la liste de toutes les offres de la base de données
        */
        $data = $em->getRepository('App:Offer')->findBy([], ['name' => 'ASC']);

        return $data;
    }
}
