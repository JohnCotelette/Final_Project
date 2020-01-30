<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Mapping\OrderBy;
use Doctrine\ORM\QueryBuilder;

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
     * @param $category
     * @param $experience
     * @param $salary
     * @param $type
     * @return array
     */
    public function findByCategoriesOrderByDate(?Category $category, ?string $experience, ?int $salary, ?string $type) :array
    {
        $qb = $this->createQueryBuilder("o");

        if ($category != null) {
            $qb
                ->andWhere(':categoryId MEMBER OF o.categories')
                ->setParameter(':categoryId', $category->getId());
        }

        if ($experience != null) {
            $qb
                ->andWhere('o.experience = :experience')
                ->setParameter(':experience', $experience);
        }

        if ($salary != null) {
            $qb
                ->andWhere('o.salary >= :salary')
                ->setParameter(':salary', $salary);
        }

        if($type != null) {
            $qb
                ->andWhere('o.type = :type')
                ->setParameter(':type', $type);
        }

        return $qb
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
