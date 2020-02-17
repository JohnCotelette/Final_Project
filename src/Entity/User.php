<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface as UUID;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="Cet email est déjà utilisé.")
 */
class User implements UserInterface
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email(
     *     message="Veuillez renseigner une adresse e-mail valide"
     * )
     * @Assert\NotBlank(
     *     message="Veuillez renseigner une adresse e-mail valide"
     * )
     * @Groups({"detailedBusiness", "detailedOffer", "candidateRegister"})
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=false)
     * @Assert\Length(
     *     min=8,
     *     max=30,
     *     minMessage="Votre mot de passe doit faire plus de {{ limit }} caractères",
     *     maxMessage="Votre mot de passe ne peut dépasser {{ limit }} caractères"
     * )
     * @Assert\NotBlank(
     *     message="Veuillez renseigner votre mot de passe"
     * )
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *     min=3,
     *     max=80,
     *     minMessage="Votre prénom doit faire plus de {{ limit }} caractères",
     *     maxMessage="Votre prénom ne peut dépasser {{ limit }} caractères"
     * )
     * @Assert\NotBlank(
     *     message="Veuillez renseigner votre prénom"
     * )
     * 
     *@Assert\Regex(
     *     pattern ="/[^A-Za-z\-]/",
     *     match=false,
     *     message="N'utilisez pas de caractères spéciaux"
     * )
     * @Groups({"detailedBusiness", "detailedOffer"})
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *     min=3,
     *     max=80,
     *     minMessage="Votre nom doit faire plus de {{ limit }} caractères",
     *     maxMessage="Votre nom ne peut dépasser {{ limit }} caractères"
     * )
     * @Assert\NotBlank(
     *     message="Veuillez renseigner votre nom"
     * )
     * @Assert\Regex(
     *     pattern ="/[^A-Za-z\-]/",
     *     match=false,
     *     message="N'utilisez pas de caractères spéciaux"
     * )
     * @Groups({"detailedBusiness", "detailedOffer"})
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *     message="La date de naissance saisie est invalide"
     * )
     * @Assert\NotNull(
     *     message="Veuillez saisir votre date de naissance"
     * )
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
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $public = true;

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
     * @Groups({"detailedBusiness"})
     */
    private $offers;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Business", inversedBy="user", cascade={"persist", "remove"})
     * @Groups({"detailedOffer"})
     */
    private $business;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }
    //UUID
    public function getId()
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

    public function setPassword(?string $password): self
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

    public function getBirthDay(): ?\DateTimeInterface
    {
        return $this->birthDay;
    }

    public function setBirthDay(?\DateTimeInterface $birthDay): self
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

    public function getPublic(): ?bool
    {
        return $this->public;
    }

    public function setPublic(?bool $public): self
    {
        $this->public = $public;

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

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): self
    {
        $this->business = $business;

        return $this;
    }

    public function __toString() :string
    {
        return $this->email;
    }
}
