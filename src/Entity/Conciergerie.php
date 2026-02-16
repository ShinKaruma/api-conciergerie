<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ConciergerieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConciergerieRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            normalizationContext: ['groups' => ['conciergerie:detail']]
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['conciergerie:list']]
        )
    ],
    normalizationContext: ['groups' => ['user:me']],
    denormalizationContext: ['groups' => ['write']],
)]
class Conciergerie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['conciergerie:list', 'conciergerie:detail', 'user:me', 'proprietaire:detail'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['conciergerie:list', 'conciergerie:detail', 'user:me', 'proprietaire:detail'])]
    private ?string $nom = null;

    #[ORM\Column(length: 5)]
    #[Groups(['conciergerie:detail'])]
    private ?string $codeSynchro = null;

    #[ORM\OneToMany(mappedBy: 'conciergerie', targetEntity: User::class)]
    #[MaxDepth(1)]
    #[Groups(['conciergerie:detail'])]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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

    public function getCodeSynchro(): ?string
    {
        return $this->codeSynchro;
    }

    public function setCodeSynchro(string $codeSynchro): static
    {
        $this->codeSynchro = $codeSynchro;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setConciergerie($this); // setter dans User renommé
        }
        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            if ($user->getConciergerie() === $this) {
                $user->setConciergerie(null);
            }
        }
        return $this;
    }
}
