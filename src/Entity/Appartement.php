<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\AppartementRepository;
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
use ApiPlatform\Metadata\Post;

#[ORM\Entity(repositoryClass: AppartementRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['appartement:detail', 'user:me']]),
        new GetCollection(
            normalizationContext: ['groups' => ['appartement:list']],
            parameters: [
                'proprietaire' => new QueryParameter(
                    property: 'proprietaire',
                    filter: new ExactFilter()
                ),
                'conciergerie' => new QueryParameter(
                    property: 'proprietaire.user.conciergerie',
                    filter: new ExactFilter()
                ),
            ]
        ),
        new Post()
    ],
    denormalizationContext: ['groups' => ['appartement:write']],
)]
class Appartement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['appartement:detail', 'appartement:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['appartement:write', 'appartement:detail', 'appartement:list'])]
    private ?string $nom = null;

    #[Groups(['appartement:write', 'appartement:detail', 'appartement:list'])]
    #[ORM\Column(length: 255)]
    private ?string $lieu = null;

    #[Groups(['appartement:write','appartement:detail', 'appartement:list'])]
    #[ORM\Column]
    private ?string $numero = null;

    #[Groups(['appartement:write', 'appartement:detail', 'appartement:list'])]
    #[ORM\Column]
    private ?string $codeCle = null;

    #[Groups(['appartement:write', 'appartement:detail', 'appartement:list'])]
    #[ORM\Column]
    private ?string $codePorte = null;

    #[ORM\Column]
    #[Groups(['appartement:write', 'appartement:detail', 'appartement:list'])]
    private ?int $nbKitDispo = 0;

    // Relation propre avec propriété lisible
    #[ORM\ManyToOne(inversedBy: 'appartements')]
    #[ORM\JoinColumn(name: 'id_proprietaire', nullable: false)]
    #[Groups(['appartement:write', 'appartement:detail', 'appartement:list'])]
    private ?Proprietaire $proprietaire = null;

    #[ORM\OneToMany(mappedBy: 'appartement', targetEntity: Location::class)]
    #[Groups(['appartement:detail'])]
    private Collection $locations;

    public function __construct()
    {
        $this->locations = new ArrayCollection();
    }

    // --- Getters / Setters ---

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

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;
        return $this;
    }

    public function getCodeCle(): ?string
    {
        return $this->codeCle;
    }

    public function setCodeCle(string $codeCle): static
    {
        $this->codeCle = $codeCle;
        return $this;
    }

    public function getCodePorte(): ?string
    {
        return $this->codePorte;
    }

    public function setCodePorte(string $codePorte): static
    {
        $this->codePorte = $codePorte;
        return $this;
    }

    public function getNbKitsDispo(): ?int
    {
        return $this->nbKitDispo;
    }

    public function setNbKitsDispo(int $nbKitDispo): static
    {
        $this->nbKitDispo = $nbKitDispo;
        return $this;
    }

    public function getProprietaire(): ?Proprietaire
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?Proprietaire $proprietaire): static
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }

    /**
     * @return Collection<int, Location>
     */
    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $location): static
    {
        if (!$this->locations->contains($location)) {
            $this->locations->add($location);
            $location->setAppartement($this);
        }
        return $this;
    }

    public function removeLocation(Location $location): static
    {
        if ($this->locations->removeElement($location)) {
            if ($location->getAppartement() === $this) {
                $location->setAppartement(null);
            }
        }
        return $this;
    }


    #[Groups(['appartement:detail', 'appartement:list'])]
    public function isOccupe(): bool
    {
        foreach ($this->locations as $location) {
            if ($location->isActive()) {
                return true;
            }
        }
        return false;
    }

    #[Groups(['appartement:detail'])]
    function getLocationActive() : ?Location {
        foreach ($this->locations as $location) {
            if ($location->isActive()) {
                return $location;
            }
        }
        return null;
    }

}
