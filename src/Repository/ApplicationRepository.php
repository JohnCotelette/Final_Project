<?php

namespace App\Repository;

use App\Entity\Application;
use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    /**
     * ApplicationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    /**
     * @param Offer $offer
     * @return mixed
     */
    public function getApplicationsByOffer(Offer $offer)
    {
        $qb = $this->createQueryBuilder("a");

        return $qb
            ->andWhere('a.offer = :offer')
            ->setParameter(':offer', $offer)
            ->getQuery()
            ->getResult();
    }
}
