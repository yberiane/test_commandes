<?php 

namespace App\Offer;

use Symfony\Component\HttpClient\HttpClient;

class CheckOffers
{
    //Check if there are compatible offers with the promo code
    public function isCompatible(array $offers, string $promoCode): array
    {
        $compatibleOffers = [];

        foreach($offers as $offer) {
            if(in_array($promoCode, $offer->getPromoCodeList())) {
                $compatibleOffers[] = ["name" => $offer->getName(), "type" => $offer->getTypeName()];
            }
        }

        return $compatibleOffers;
    }
}
