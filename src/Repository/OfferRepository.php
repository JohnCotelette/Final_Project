<?php

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\ORM\Query;
use App\Entity\Business;
use App\Entity\Category;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OfferRepository extends ServiceEntityRepository
{
    /**
     * OfferRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    /**
     * @param Category|null $category
     * @param string|null $experience
     * @param int|null $salary
     * @param string|null $type
     * @param string|null $city
     * @return array
     */
    public function findByCategoriesOrderByDate(?Category $category, ?string $experience, ?int $salary, ?string $type, ?string $location) :array
    {
        $defaultExperience = "Tous";

        $qb = $this->createQueryBuilder("o");

        if ($category != null) {
            $qb
                ->andWhere(':category MEMBER OF o.categories')
                ->setParameter(':category', $category);
        }

        if ($experience != null) {
            $qb
                ->andWhere('o.experience = :experience')
                ->orWhere('o.experience = :defaultExperience')
                ->setParameter(':experience', $experience)
                ->setParameter(':defaultExperience', $defaultExperience);
        }
        else {
            $qb
                ->andWhere('o.experience IS NOT NULL');
        }

        if ($salary != null) {
            $qb
                ->andWhere('o.salary >= :salary')
                ->setParameter(':salary', $salary);
        }

        if ($type != null) {
            $qb
                ->andWhere('o.type = :type')
                ->setParameter(':type', $type);
        }

        if ($location != null) {
            $qb
                ->andWhere('o.location LIKE :location')
                ->setParameter(':location', "%$location%");
        }

        return $qb
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findOffersByBusinessOrderByDate(Business $business) 
    {
        $qb = $this->createQueryBuilder('o');

        return $qb
            ->where('o.user = :user')
            ->setParameter(':user', $business->getUser())
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
