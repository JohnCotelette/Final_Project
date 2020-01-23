<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use App\Entity\Field;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;

/**
 * Class FieldFixture
 * @package App\DataFixtures
 */
class CategoryFixture extends BaseFixture implements DependentFixtureInterface
{
    const MARKETING_CATEGORIES = [
        "Community Manager",
        "Responsable Marketing",
        "Responsable Communication",
        "Digital Strategist",
    ];

    const DATA_CATEGORIES = [
        "Data Scientist",
        "Data Miner",
        "Data Analyst",
        "Architecte Big Data",
    ];

    const CREATION_CATEGORIES = [
        "Directeur Artistique",
        "Maquettiste",
        "Designer",
        "Designer UX/UI",
    ];

    const TECH_CATEGORIES = [
        "Développeur Web Full-Stack",
        "Développeur Javascript",
        "Développeur Front-End",
        "Développeur Back-End",
        "Développeur PHP/Symfony"
    ];

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @var int
     */
    private $indexForLoop = 0;

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $marketingField = $this->getSpecificField("Marketing");

        $this->createManyWithoutReferenceConflicts(Category::class, count(self::MARKETING_CATEGORIES), function(Category $category) use ($marketingField) {
            $category
                ->setName(self::MARKETING_CATEGORIES[$this->index])
                ->setField($marketingField);
            ;

            $this->index++;
            $this->indexForLoop++;
        });

        $dataField = $this->getSpecificField("Data");

        $this->createManyWithoutReferenceConflicts(Category::class, count(self::DATA_CATEGORIES), function(Category $category) use ($dataField) {
            $category
                ->setName(self::DATA_CATEGORIES[$this->index])
                ->setField($dataField);
            ;

            $this->index++;
            $this->indexForLoop++;
        });

        $creationField = $this->getSpecificField("Création");

        $this->createManyWithoutReferenceConflicts(Category::class, count(self::CREATION_CATEGORIES), function(Category $category) use ($creationField) {
            $category
                ->setName(self::CREATION_CATEGORIES[$this->index])
                ->setField($creationField);
            ;

            $this->index++;
            $this->indexForLoop++;
        });

        $techField = $this->getSpecificField("Tech");

        $this->createManyWithoutReferenceConflicts(Category::class, count(self::TECH_CATEGORIES), function(Category $category) use ($techField) {
            $category
                ->setName(self::TECH_CATEGORIES[$this->index])
                ->setField($techField);
            ;

            $this->index++;
            $this->indexForLoop++;
        });

        $this->manager->flush();
    }

    public function getSpecificField(string $type)
    {
        $field = $this->getRandomReference(Field::class);

        if ($field->getName() !== $type) {
            return $this->getSpecificField($type);
        }
        else {
            return $field;
        }
    }

    public function createManyWithoutReferenceConflicts(string $className, int $count, callable $factory)
    {
        $number = $this->indexForLoop + $count;

        for ($i = $this->indexForLoop; $i <= $number - 1; $i++) {
            $entity = new $className();
            $factory($entity, $i);

            $this->manager->persist($entity);
            $this->addReference($className . '_' . $i, $entity);
        }

        $this->index = 0;
    }

    /**
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            FieldFixture::class,
        ];
    }
}