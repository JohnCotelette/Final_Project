<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use App\Entity\Category;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\OfferService;
use App\Entity\Offer;
use App\Entity\User;

/**
 * Class OfferFixture
 * @package App\DataFixtures
 */
class OfferFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @var OfferService;
     */
    private $offerService;

    /**
     * @var int
     */
    private $index = 0;

    const OFFERS_EXPERIENCES = [
        "Tous",
        "Junior (0 à 2 ans)",
        "Confirmé (3 à 6 ans)",
        "Senior (7 ans et plus)",
    ];

    const OFFERS_TYPE = [
        "CDI",
        "CDD",
        "Stage",
    ];

    const OFFERS_SALARY = [
        25000,
        28000,
        32000,
        40000,
        58000,
        53000,
    ];

    /**
     * OfferFixture constructor.
     * @param OfferService $offerService
     */
    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Offer::class, 80, function(Offer $offer) {
            $randomRecruiter = $this->getRandomRecruiter();

            $randomNumberOfCategories = rand(1, 3);

            $chanceToSetASalary = rand(0, 5);

            $offer
                ->setTitle($this->faker->text($minNbChars = 30, $maxNbChars = 50))
                ->setDescription($this->faker->text($minNbChars = 1500, $maxNbChars = 2000))
                ->setExperience(self::OFFERS_EXPERIENCES[rand(0, 3)])
                ->setType(self::OFFERS_TYPE[rand(0, 2)])
                ->setLocation($this->faker->city)
                ->setCreatedAt($this->faker->dateTimeBetween($startDate = "-1 month", $endDate = "now", $timezone = "Europe/Paris"))
                ->setStartedAt($this->faker->dateTimeBetween($startDate = "now", $endDate = "+ 1 year", $timezone = "Europe/Paris"))
                ->setProfilRequired($this->faker->text($minNbChars = 500, $maxNbChars = 800));

            if ($chanceToSetASalary > 0) {
                $offer->setSalary(self::OFFERS_SALARY[rand(0, count(self::OFFERS_SALARY) - 1)]);
            }

            for ($i = 0; $i < $randomNumberOfCategories; $i++) {
                $uniqueCategory = $this->getUniqueReferenceOfCategory($offer);

                $offer->addCategory($uniqueCategory);
            }

            $this->offerService->generateReference($offer);

            $randomRecruiter->addOffer($offer);

            $this->index++;
        });

        $manager->flush();
    }

    /**
     * @return mixed
     */
    public function getRandomRecruiter()
    {
        $randomUser = $this->getRandomReference(User::class);

        if (!in_array("ROLE_RECRUITER", $randomUser->getRoles())) {
            return $this->getRandomRecruiter();
        }
        else {
            return $randomUser;
        }
    }

    public function getUniqueReferenceOfCategory(Offer $offer)
    {
        $uniqueReference = $this->getRandomReference(Category::class);

        if ($offer->getCategories()->contains($uniqueReference)) {
            return $this->getUniqueReferenceOfCategory($offer);
        }

        return $uniqueReference;
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            CategoryFixture::class,
        ];
    }
}