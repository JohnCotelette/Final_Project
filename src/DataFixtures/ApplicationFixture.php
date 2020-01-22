<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Application;
use App\Entity\Offer;

/**
 * Class ApplicationFixture
 * @package App\DataFixtures
 */
class ApplicationFixture extends BaseFixture implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(Application::class, 5, function (Application $application) {
            $randomCandidate = $this->getRandomCandidate();

            $application
                ->setMotivation($this->faker->text($maxNbChars = 200))
                ->setOffer($this->getRandomReference(Offer::class))
                ->setUser($randomCandidate)
            ;
        });

        $manager->flush();
    }

    /**
     * @return mixed
     */
    public function getRandomCandidate()
    {
        $randomCandidate = $this->getRandomReference(User::class);

        if (!in_array("ROLE_CANDIDATE", $randomCandidate->getRoles())) {
            return $this->getRandomCandidate();
        }
        else {
            return $randomCandidate;
        }
    }

    /**
     * @return array
     */
    public function getDependencies()
    {
        return [
            UserFixture::class,
            OfferFixture::class,
        ];
    }
}