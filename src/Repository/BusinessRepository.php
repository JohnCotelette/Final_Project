<?php

namespace App\Repository;

use App\Entity\Business;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Business|null find($id, $lockMode = null, $lockVersion = null)
 * @method Business|null findOneBy(array $criteria, array $orderBy = null)
 * @method Business[]    findAll()
 * @method Business[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BusinessRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Business::class);
    }

    public function getAllBusinessWhereDescAndWhyUsNotNullOrderByName()
    {
        return $qb = $this->createQueryBuilder('b')
            ->andWhere('b.description IS NOT NULL')
            ->andWhere('b.whyUs IS NOT NULL')
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getAllBusinessWhichHaveOffers()
    {
        return $qb = $this->createQueryBuilder('b')
            ->leftJoin('b.user', 'bu')
            ->where('bu.offers IS NOT EMPTY')
            ->orderBy('b.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
