<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Service\OfferService;
use App\Entity\Offer;
use App\Entity\User;

/**
 * Class UserFixture
 * @package App\DataFixtures
 */
class OfferFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @var OfferService;
     */
    private $offerService;

    const OFFERS_TITLES = [
        "Développeur Symfony",
        "Développeur Javascript Node.js",
        "Chef de projet",
        "Directeur artistique",
        "Lead Développeur",
    ];

    const OFFERS_DESCRIPTIONS = [
        "Nous recherchons un développeur Symfony pour le développement de notre application interne de gestion des dossiers.",
        "Si tu aimes le boulot en équipe et que tu aimes JS (back et front), ce boulot est pour toi !",
        "Nous recherchons un chef de projet pour renforcer nos équipes.",
        "Nous sommes à la recherche d'un as de Photoshop/Illustrator pour maqueter du Web.",
        "Si la puissance du développement est en toi et que tu te sens l'âme d'un manager, nous serons heureux de t'accueilir parmi nous !"
    ];

    const OFFERS_EXPERIENCES = [
        "Débutants acceptés",
        "1 an d'experience",
        "6 mois d'experience",
        "7 ans d'experience",
        "5 ans d'experience",
    ];

    const OFFERS_SALARY = [
        null,
        null,
        "35000",
        "30000",
        "40000",
    ];

    const OFFERS_PROFIL_REQUIRED = [
        "Organisé et motivé.",
        "Curieux.",
        "Rigoureux et qui aime le travail en équipe.",
        "Maitrise de la suite Adobe.",
        "Capacités en management et organisé."
    ];

    const OFFERS_TYPE = [
        "CDI",
        "CDD",
        "Stage",
    ];

    private $index = 0;

    public function __construct(OfferService $offerService)
    {
        $this->offerService = $offerService;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $randomRecruiter = $this->getRandomRecruiter();

        $this->createMany(Offer::class, 5, function(Offer $offer) use ($randomRecruiter) {
            $offer
                ->setTitle(self::OFFERS_TITLES[$this->index])
                ->setDescription(self::OFFERS_DESCRIPTIONS[$this->index])
                ->setExperience(self::OFFERS_EXPERIENCES[$this->index])
                ->setSalary(self::OFFERS_SALARY[$this->index])
                ->setType(self::OFFERS_TYPE[rand(0, 2)])
                ->setLocation($this->faker->city)
                ->setProfilRequired(self::OFFERS_PROFIL_REQUIRED[$this->index])
                ->setStartedAt($this->faker->dateTimeBetween($startDate = "now", $endDate = "+ 1 year", $timezone = "Europe/Paris"))
            ;

            $this->offerService->generateReference($offer);

            $randomRecruiter->addOffer($offer);

            $this->index++;
        });

        $manager->flush();
    }

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

    public function getDependencies()
    {
        return [
            UserFixture::class,
        ];
    }
}