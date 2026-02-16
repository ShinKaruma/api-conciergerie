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

#[ORM\Entity(repositoryClass: LocationRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[ORM\ManyToOne(inversedBy: 'locations')]
    private ?Appartement $appartement = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
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

    public function isActive(): bool {
        $now = new DateTimeImmutable('now', new DateTimeZone('Europe/Paris'));

        return $this->dateDebut <= $now
            && $this->dateFin >= $now;
    }


}
