<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\LocataireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Metadata\Post;

#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['location:detail']]),
    ],
    denormalizationContext: ['groups' => ['location:write']],
)]
#[ORM\Entity(repositoryClass: LocataireRepository::class)]
class Locataire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    private ?int $id = null;

    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[Groups(['location:detail', 'location:list', 'location:write', 'appartement:detail', 'planning:read'])]
    #[ORM\Column(length: 255)]
    private ?string $telephone = null;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }
}
