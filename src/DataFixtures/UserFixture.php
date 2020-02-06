<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Business;

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

    const EMPLOYEES_NUMBER = [
            "19 employés et moins",
            "20 à 99 employés",
            "100 à 499 employés",
            "500 employés et plus",
        ];

    const BUSINESS_KIND = [
        "Cabinet de recrutement",
        "Editeur de logiciel",
        "Entreprise",
        "ESN / Cabinet de conseil",
    ];

    const BUSINESS_CITY = [
        "Lyon",
        "Lille",
        "Paris",
        "Toulouse",
    ];

    const BUSINESS_ACTIVITYAREA = [
        "Industrie & Énergie",
        "Média & Télécom",
        "Web & Tech",
        "Jeux vidéo",
        "Banque & Assurance",
    ];

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
        $this->createMany(User::class, 101, function(User $user) {
            if ($this->index % 2 === 0 && $this->index < 100) {
                $user
                    ->setEmail(self::DEFAULT_CANDIDATE . $this->index . self::DEFAULT_ENDMAIL)
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName($this->faker->firstname)
                    ->setLastName($this->faker->lastname)
                    ->setIsActive(true)
                    ->setRoles(["ROLE_CANDIDATE"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                    ;
            } else if ($this->index % 2 !== 0 && $this->index < 100) {
                $business = new Business();

                $business
                    ->setEmployeesNumber(self::EMPLOYEES_NUMBER[rand(0, 3)])
                    ->setName($this->faker->company)
                    ->setLocation($this->faker->streetAddress . ", " . self::BUSINESS_CITY[rand(0,3)])
                    ->setSiretNumber(rand(11111111111111, 99999999999999))
                    ->setActivityArea(self::BUSINESS_ACTIVITYAREA[rand(0, 4)])
                    ->setWhyUs($this->faker->text($maxNbChars = 2000))
                    ->setDescription($this->faker->text($maxNbChars = 2000))
                    ->setKind(self::BUSINESS_KIND[rand(0,3)])
                    ;

                $user
                    ->setEmail(self::DEFAULT_RECRUITER . $this->index . self::DEFAULT_ENDMAIL)
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName($this->faker->firstname)
                    ->setLastName($this->faker->lastname)
                    ->setIsActive(true)
                    ->setRoles(["ROLE_RECRUITER"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                    ->setBusiness($business)
                    ;
            } else {
                $user
                    ->setEmail("admin@findlab.com")
                    ->setPassword($this->passwordEncoder->encodePassword($user, "12345678"))
                    ->setFirstName("Robert")
                    ->setLastName("Hue")
                    ->setIsActive(true)
                    ->setRoles(["ROLE_ADMIN"])
                    ->setBirthDay($this->faker->dateTimeBetween($startDate = "-60 years", $endDate = "- 18 years", $timezone = "Europe/Paris"))
                    ;
            }

            $this->index++;
        });

        $manager->flush();
    }
}