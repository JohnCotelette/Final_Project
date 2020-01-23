<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

/**
 * Class UserFixture
 * @package App\DataFixtures
 */
class UserFixture extends BaseFixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    private $index = 0;

    const DEFAULT_ENDMAIL = "@hotmail.fr";

    const DEFAULT_CANDIDATE = "candidate";

    const DEFAULT_RECRUITER = "recruiter";

    /**
     * UserFixture constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadData(ObjectManager $manager)
    {
        $this->createMany(User::class, 20, function(User $user) {
            if ($this->index % 2 === 0) {
                $user
                    ->setEmail(self::DEFAULT_CANDIDATE . $this->index . self::DEFAULT_ENDMAIL)
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName($this->faker->firstname)
                    ->setLastName($this->faker->lastname)
                    ->setIsActive(1)
                    ->setRoles(["ROLE_CANDIDATE"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"));
            } else {
                $user
                    ->setEmail(self::DEFAULT_RECRUITER . $this->index . self::DEFAULT_ENDMAIL)
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName($this->faker->firstname)
                    ->setLastName($this->faker->lastname)
                    ->setIsActive(1)
                    ->setRoles(["ROLE_RECRUITER"])
                    ->setBusiness($this->faker->company)
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                ;
            }

            $this->index++;
        });

        $manager->flush();
    }
}