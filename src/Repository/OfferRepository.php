<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Offer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
    public function findByCategoriesOrderByDate(?Category $category, ?string $experience, ?int $salary, ?string $type, ?string $city) :array
    {
        $defaultExperience = "Tous";

        $qb = $this->createQueryBuilder("o");

        if ($category != null) {
            $qb
                ->andWhere(':categoryId MEMBER OF o.categories')
                ->setParameter(':categoryId', $category->getId());
        }

        if ($experience != null) {
            $parameters = [
                "experience" => $experience,
                "defaultExperience" => $defaultExperience,
            ];

            $qb
                ->andWhere('o.experience = :experience')
                ->orWhere('o.experience = :defaultExperience')
                ->setParameter(':experience', $experience)
                ->setParameter(':defaultExperience', $defaultExperience);
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

        if ($city != null) {
            $qb
                ->andWhere('o.location = :city')
                ->setParameter(':city', $city);
        }

        return $qb
            ->orderBy('o.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
