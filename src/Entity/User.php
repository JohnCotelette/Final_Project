<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\Length(
     *     min=8,
     *     max=30,
     *     minMessage="Your password must be at least {{ limit }} characters long.",
     *     maxMessage="Your password cannot be longer than {{ limit }} characters."
     * )
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *     min=3,
     *     max=80,
     *     minMessage="Your firstname must be at least {{ limit }} characters long.",
     *     maxMessage="Your firstname cannot be longer than {{ limit }} characters."
     * )
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *     min=3,
     *     max=80,
     *     minMessage="Your firstname must be at least {{ limit }} characters long.",
     *     maxMessage="Your firstname cannot be longer than {{ limit }} characters."
     * )
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $birthDay;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = false;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $passwordToken;

    /**
     * @ORM\Column(type="string", length=70, nullable=true)
     * @Assert\Length(
     *     min=3,
     *     max=80,
     *     minMessage="Your firstname must be at least {{ limit }} characters long.",
     *     maxMessage="Your firstname cannot be longer than {{ limit }} characters."
     * )
     * @Assert\NotBlank()
     * 
     */
    private $business;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Avatar", cascade={"persist", "remove"})
     */
    private $avatar;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Cv", inversedBy="user", cascade={"persist", "remove"})
     */
    private $cv;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="user", orphanRemoval=true)
     */
    private $applications;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="user", orphanRemoval=true)
     */
    private $offers;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthDay;
    }

    public function setBirthday(\DateTimeInterface $birthDay): self
    {
        $this->birthDay = $birthDay;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(?bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getPasswordToken(): ?string
    {
        return $this->passwordToken;
    }

    public function setPasswordToken(?string $passwordToken): self
    {
        $this->passwordToken = $passwordToken;

        return $this;
    }

    public function getBusiness(): ?string
    {
        return $this->business;
    }

    public function setBusiness(?string $business): self
    {
        $this->business = $business;

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

    public function getCv(): ?Cv
    {
        return $this->cv;
    }

    public function setCv(?Cv $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    /**
     * @return Collection|Application[]
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setUser($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->contains($application)) {
            $this->applications->removeElement($application);
            // set the owning side to null (unless already changed)
            if ($application->getUser() === $this) {
                $application->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setUser($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getUser() === $this) {
                $offer->setUser(null);
            }
        }

        return $this;
    }
}
