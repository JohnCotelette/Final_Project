<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Field;

/**
 * Class FieldFixture
 * @package App\DataFixtures
 */
class FieldFixture extends BaseFixture
{
    const FIELDS_NAMES = [
        "Création",
        "Data",
        "Tech",
        "Marketing",
    ];

    const FIELDS_COLORS = [
        "#CF3515",
        "#1A5AD9",
        "#09B814",
        "#7609B8",
    ];

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Field::class, 4, function(Field $field) {
            $field
                ->setName(self::FIELDS_NAMES[$this->index])
                ->setColor(self::FIELDS_COLORS[$this->index])
                ;

            $this->index++;
        });

        $manager->flush();
    }
}