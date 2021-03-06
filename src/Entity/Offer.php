<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"detailedBusiness", "detailedOffer"})
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\DateTime(
     *     message="Le format de la date de prise de poste est invalide"
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $started_at;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expired_at;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=90)
     * @Assert\NotBlank(
     *     message="Veuillez renseigner un titre"
     * )
     * @Assert\Length(
     *     min=8,
     *     max=90,
     *     minMessage="Le titre de l'offre doit faire au minimum {{ limit }} caractères",
     *     maxMessage="Le titre de l'offre ne peut dépasser {{ limit }} caractères",
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message="Veuillez renseigner une description"
     * )
     * @Assert\Length(
     *     min=100,
     *     max=2000,
     *     minMessage="La description de l'offre doit faire au minimum {{ limit }} caractères",
     *     maxMessage="La description de l'offre ne peut dépasser {{ limit }} caractères",
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(
     *     message="Veuillez décrire le profil requis"
     * )
     * @Assert\Length(
     *     min=80,
     *     max=2000,
     *     minMessage="La description de l'offre doit faire au minimum {{ limit }} caractères",
     *     maxMessage="La description de l'offre ne peut dépasser {{ limit }} caractères",
     * )
     * @Groups({"detailedBusiness", "detailedOffer"})
     */
    private $profilRequired;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(
     *     message="Veuillez renseigner une adresse (complète ou non)"
     * )
     * @Assert\Length(
     *     min=4,
     *     max=100,
     *     minMessage="L'adresse de l'offre doit faire au minimum {{ limit }} caractères",
     *     maxMessage="L'adresse de l'offre ne peut dépasser {{ limit }} caractères",
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $location;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Tous', 'Junior (0 à 2 ans)', 'Confirmé (3 à 6 ans)', 'Senior (7 ans et plus)')")
     * @Assert\NotBlank(
     *     message="Veuillez renseigner l'experience requise"
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $experience;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Range(
     *     min="0",
     *     max="1000000",
     *     minMessage="Le salaire de l'offre ne peut être un chiffre négatif",
     *     maxMessage="Le salaire de l'offre ne peut dépasser {{ limit }} brut annuel",
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $salary;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('CDI', 'CDD', 'Stage')")
     * @Assert\NotBlank(
     *     message="Veuillez renseigner le type de contrat"
     * )
     * @Groups({"detailedBusiness", "lightOffer", "detailedOffer"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"detailedOffer"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Application", mappedBy="offer", orphanRemoval=true)
     */
    private $applications;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Category", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\Count(
     *     min=1,
     *     max=3,
     *     minMessage="Vous devez renseigner au moins {{ limit }} catégorie",
     *     maxMessage="Vous ne pouvez selectionner plus de {{ limit }} catégories"
     * )
     * @Groups({"lightOffer", "detailedOffer"})
     */
    private $categories;

    /**
     * Offer constructor.
     */
    public function __construct()
    {
        $this->created_at = new \DateTime();
        $this->applications = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStartedAt(): ?\DateTimeInterface
    {
        return $this->started_at;
    }

    public function setStartedAt(?\DateTimeInterface $started_at): self
    {
        $this->started_at = $started_at;

        return $this;
    }

    public function getExpiredAt(): ?\DateTimeInterface
    {
        return $this->expired_at;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     * @return Offer
     * @throws \Exception
     */
    public function setExpiredAt(): self
    {
        $now = new \DateTime();

        $this->expired_at = $now->add(new \DateInterval("P2M"));

        return $this;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProfilRequired(): ?string
    {
        return $this->profilRequired;
    }

    public function setProfilRequired(string $profilRequired): self
    {
        $this->profilRequired = $profilRequired;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(string $experience): self
    {
        $this->experience = $experience;

        return $this;
    }

    public function getSalary(): ?string
    {
        if ($this->salary == null) {
            return "A négocier";
        }

        return $this->salary . " brut annuel";
    }

    public function setSalary(?int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

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
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->contains($application)) {
            $this->applications->removeElement($application);
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Category[]
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories[] = $category;
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->title;
    }
}
