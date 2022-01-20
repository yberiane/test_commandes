<?php 

namespace App\PromoCode;

use Symfony\Component\HttpClient\HttpClient;
use App\Entity\PromoCode;

class CheckPromoCodes
{
    //check if the promo code exists
    public function exist(array $promoCodes, $promoCodeToValidate): ?PromoCode
    {
        foreach($promoCodes as $promoCode) {
            if($promoCode->getCode() === $promoCodeToValidate) {
                return $promoCode;
            }
        }
        return NULL;
    }
}
