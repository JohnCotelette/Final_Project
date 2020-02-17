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

    const OFFERS_TITLES = [
        "Développeur PHP/Symfony",
        "Chef de projet Web",
        "Directeur artistique",
        "Data miner",
        "Data analyst",
        "Développeur full stack",
        "Développeur Javascript",
        "Chargé de programmation",
        "Consultant technique",
        "Consultant SEO",
        "Designer UX/UI",
        "Formateur multimédia",
        "Graphiste multimédia",
        "Web Designer",
    ];

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
        0,
        25000,
        28000,
        32000,
        40000,
        58000,
        53000,
    ];

    const OFFER_ADDRESS = [
        "19 rue du Président Édouard Herriot, 69001 Lyon",
        "40 Avenue de Clichy, 75018 Paris",
        "34, boulevard Charles-Livon, Marseille",
        "51 rue Basse, 59800 Lille",
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
        $this->createMany(Offer::class, 50, function(Offer $offer) {
            $randomRecruiter = $this->getRandomRecruiter();

            $randomNumberOfCategories = rand(1, 3);

            $offer
                ->setTitle(self::OFFERS_TITLES[rand(0, 13)])
                ->setDescription($this->faker->text($minNbChars = 1500, $maxNbChars = 2000))
                ->setExperience(self::OFFERS_EXPERIENCES[rand(0, 3)])
                ->setType(self::OFFERS_TYPE[rand(0, 2)])
                ->setCreatedAt($this->faker->dateTimeBetween($startDate = "-1 month", $endDate = "now", $timezone = "Europe/Paris"))
                ->setStartedAt($this->faker->dateTimeBetween($startDate = "now", $endDate = "+ 1 year", $timezone = "Europe/Paris"))
                ->setProfilRequired($this->faker->text($minNbChars = 500, $maxNbChars = 800))
                ->setSalary(self::OFFERS_SALARY[rand(0, count(self::OFFERS_SALARY) - 1)]);

            $chanceToHaveALocationDifferentFromBusiness = rand(0, 3);

            if ($chanceToHaveALocationDifferentFromBusiness > 0) {
                $offer->setLocation($randomRecruiter->getBusiness()->getLocation());
            }
            else {
                $offer->setLocation(self::OFFER_ADDRESS[rand(0, 3)]);
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