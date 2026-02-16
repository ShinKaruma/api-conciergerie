<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
#[ApiResource]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?int $tarifLocataire = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 2)]
    private ?string $temps = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getTarifLocataire(): ?int
    {
        return $this->tarifLocataire;
    }

    public function setTarifLocataire(int $tarifLocataire): static
    {
        $this->tarifLocataire = $tarifLocataire;
        return $this;
    }

    public function getTemps(): ?string
    {
        return $this->temps;
    }

    public function setTemps(string $temps): static
    {
        $this->temps = $temps;
        return $this;
    }
}
