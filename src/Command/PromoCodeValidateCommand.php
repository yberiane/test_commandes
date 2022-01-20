<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use App\Service\OfferService;
use App\Offer\CheckOffers;
use App\Service\PromoCodeService;
use App\Entity\PromoCode;
use App\PromoCode\CheckPromoCodes;
use App\FileManager\FileManager;
use Doctrine\ORM\EntityManagerInterface;

class PromoCodeValidateCommand extends Command
{
    protected static $defaultName = 'pc:validate';
    protected static $defaultDescription = 'Check a promo code validity and return compatible offers';
    private $em;

    public function __construct(EntityManagerinterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('promo-code', InputArgument::REQUIRED, 'Promo code to validate')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $promoCodeToValidate = $input->getArgument('promo-code');
        
        $offerService = new OfferService();
        $checkOffers = new CheckOffers();

        $promoCodeService = new PromoCodeService();
        $checkPromoCodes = new CheckPromoCodes();

        $promoCodes = $promoCodeService->getPromoCodes($this->em);

        
        //Check if the promo code exists.
        //If so, returns a PromoCode object, otherwise returns NULL
        $promoCode = $checkPromoCodes->exist($promoCodes, $promoCodeToValidate);

        if(!($promoCode instanceof PromoCode)) {
            $io->warning('The promo code does not exist ! Please check and try again.');
            return Command::FAILURE;
        }

        if(!($promoCode->isValid())) {
            $io->warning('The promo code is no longer valid.');
            return Command::FAILURE;
        }

        $offers = $offerService->getOffers($this->em);

        /* Check if there are compatible offers for the promoCode.
           Returns : 
           array(
               array(
                   "name" => $name,
                   "type" => $type
               ),
               array(
                   "name" => $name,
                   "type" => $type
               )
            )
        */
        $compatibleOfferlist = $checkOffers->isCompatible($offers, $promoCode->getCode());

        if(empty($compatibleOfferlist)) {
            $io->success('No offer is compatible with this promo code !');
            return Command::SUCCESS;
        }

        $fileManager = new FileManager();
        
        //Create promo_code directory if not exists and create or update the json file
        $fileName = $fileManager->write($compatibleOfferlist, $promoCode);

        if(!isset($fileName)) {
            $io->warning('Failed to create the json file. Try again.');     
            return Command::FAILURE;
        }

        $io->success('The promo code is valid ! check in var/promo_code/' . $fileName . ' the corresponding offers.');     
        return Command::SUCCESS;
    }
}
