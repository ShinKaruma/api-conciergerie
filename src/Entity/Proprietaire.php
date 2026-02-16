<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProprietaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\QueryParameter;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;

#[ORM\Entity(repositoryClass: ProprietaireRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['proprietaire:detail', 'user:me']]),
        new GetCollection(
            normalizationContext: ['groups' => ['proprietaire:list']],
            parameters: [
                'user' => new QueryParameter(
                    property: 'user',
                    filter: new ExactFilter()
                )
            ]
        )
    ],
    denormalizationContext: ['groups' => ['write']],
)]
class Proprietaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:me', 'proprietaire:detail', 'proprietaire:list', 'appartement:detail', 'appartement:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:me', 'proprietaire:detail', 'proprietaire:list', 'appartement:detail', 'appartement:list'])]
    private ?string $couleur = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ApiProperty(readableLink: true)]
    #[MaxDepth(1)]
    #[Groups(['proprietaire:detail', 'proprietaire:list', 'appartement:detail', 'appartement:list'])]
    private ?User $user = null;

    #[Groups(['proprietaire:detail'])]
    #[ORM\OneToMany(targetEntity: Appartement::class, mappedBy: 'proprietaire', orphanRemoval: true)]
    private Collection $appartements;

    public function __construct()
    {
        $this->appartements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Appartement>
     */
    public function getAppartements(): Collection
    {
        return $this->appartements;
    }

    public function addAppartement(Appartement $appartement): static
    {
        if (!$this->appartements->contains($appartement)) {
            $this->appartements->add($appartement);
            $appartement->setProprietaire($this); // setter renommé
        }
        return $this;
    }

    public function removeAppartement(Appartement $appartement): static
    {
        if ($this->appartements->removeElement($appartement)) {
            if ($appartement->getProprietaire() === $this) {
                $appartement->setProprietaire(null);
            }
        }
        return $this;
    }
}
