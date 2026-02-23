<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\DBAL\Types\Types;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\QueryParameter;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Metadata\Post;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['location:detail']]),
        new GetCollection(
            normalizationContext: ['groups' => ['location:list']],
            parameters: [
                'appartement' => new QueryParameter(
                    property: 'appartement',
                    filter: new ExactFilter()
                ),
            ]
        ),
        new Post()
    ],
    denormalizationContext: ['groups' => ['location:write']],
)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    private ?int $id = null;

    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    #[Groups(['location:detail', 'location:list', 'location:write', 'planning:read'])]
    private ?Appartement $appartement = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    private ?Locataire $locataire = null;

    /**
     * @var Collection<int, LocationService>
     */
    #[ORM\OneToMany(targetEntity: LocationService::class, mappedBy: 'location', orphanRemoval: true)]
    private Collection $services;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    public function getAppartement(): ?Appartement
    {
        return $this->appartement;
    }

    public function setAppartement(?Appartement $appartement): static
    {
        $this->appartement = $appartement;
        return $this;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataire $locataire): static
    {
        $this->locataire = $locataire;
        return $this;
    }

    /**
     * @return Collection<int, LocationService>
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    public function addService(LocationService $service): static
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
            $service->setLocation($this);
        }

        return $this;
    }

    public function removeService(LocationService $service): static
    {
        if ($this->services->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getLocation() === $this) {
                $service->setLocation(null);
            }
        }

        return $this;
    }

    public function getCouleurProprietaire() : ?string {
        return $this->appartement->getProprietaire()->getCouleur();
    }

    #[Groups(['location:detail', 'location:list', 'appartement:detail'])]
    public function isActive(): bool {
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));

        return $this->dateDebut <= $now
            && $this->dateFin >= $now;
    }


}
