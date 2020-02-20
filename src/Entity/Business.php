<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BusinessRepository")
 */
class Business
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"lightBusiness", "detailedBusiness"})
     */
    private $id;

    /**
     * @ORM\Column(type="bigint", length=14)
     * @Groups({"lightBusiness", "detailedBusiness"})
     */
    private $siretNumber;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"lightBusiness", "detailedBusiness", "detailedOffer"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('19 employés et moins', '20 à 99 employés', '100 à 499 employés', '500 employés et plus')", nullable=true)
     * @Groups({"lightBusiness", "detailedBusiness", "detailedOffer"})
     */
    private $employeesNumber;

    /**
     * @ORM\Column(type="text", length=5000, nullable=true)
     * @Groups({"detailedBusiness"})
     */
    private $description;

    /**
     * @ORM\Column(type="text", length=5000, nullable=true)
     */
    private $whyUs;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"lightBusiness", "detailedBusiness"})
     */
    private $activityArea;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"lightBusiness", "detailedBusiness", "detailedOffer"})
     */
    private $location;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"lightBusiness", "detailedBusiness", "detailedOffer"})
     */
    private $kind;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="business", cascade={"persist", "remove"})
     * @Groups({"detailedBusiness"})
     */
    private $user;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Avatar", cascade={"persist", "remove"})
     */
    private $avatar;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSiretNumber(): ?string
    {
        return $this->siretNumber;
    }

    public function setSiretNumber(string $siretNumber): self
    {
        $this->siretNumber = $siretNumber;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getEmployeesNumber(): ?string
    {
        return $this->employeesNumber;
    }

    public function setEmployeesNumber(?string $employeesNumber): self
    {
        $this->employeesNumber = $employeesNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWhyUs(): ?string
    {
        return $this->whyUs;
    }

    public function setWhyUs(?string $whyUs): self
    {
        $this->whyUs = $whyUs;

        return $this;
    }

    public function getActivityArea(): ?string
    {
        return $this->activityArea;
    }

    public function setActivityArea(?string $activityArea): self
    {
        $this->activityArea = $activityArea;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getKind(): ?string
    {
        return $this->kind;
    }

    public function setKind(?string $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newBusiness = null === $user ? null : $this;
        if ($user->getBusiness() !== $newBusiness) {
            $user->setBusiness($newBusiness);
        }

        return $this;
    }

    public function getAvatar(): ?Avatar
    {
        return $this->avatar;
    }

    public function setAvatar(?Avatar $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function __toString() :string
    {
        return $this->name;
    }
}
