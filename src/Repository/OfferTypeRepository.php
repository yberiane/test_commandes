<?php

namespace App\Repository;

use App\Entity\OfferType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OfferType|null find($id, $lockMode = null, $lockVersion = null)
 * @method OfferType|null findOneBy(array $criteria, array $orderBy = null)
 * @method OfferType[]    findAll()
 * @method OfferType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OfferType::class);
    }
}
