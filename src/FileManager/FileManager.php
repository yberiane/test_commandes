<?php 

namespace App\FileManager;

use Symfony\Component\Filesystem\Filesystem;
use App\Entity\PromoCode;

class FileManager
{
    public function write(array $offers, PromoCode $promoCode): string 
    {
        $fileSystem = new Filesystem();
        $path = 'var/promo_code';
        $fileName = $promoCode->getCode() . "_" . date('Ymd') . ".json";

        //create promo_code directory if not exists
        if(!$fileSystem->exists($path)) {
            $fileSystem->mkdir($path);
        }

        $file = array(
            "promoCode" => $promoCode->getCode(),
            "endDate" => $promoCode->getEndDate()->format('Y-m-d'),
            "discountValue" => strval($promoCode->getDiscountValue()),
            "compatibleOfferList" => $offers
        );

        $json = json_encode($file);
        
        //create or update the json file corresponding to the promo code
        $bytes = file_put_contents($path . '/' . $fileName, $json); 

        if(false === $bytes) {
            return NULL;
        }

        return $fileName;
    }
}
