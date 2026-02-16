<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Serializer\Annotation\Ignore;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ApiResource(
    normalizationContext: ['groups' => [
        'user:me'
        ]],
    denormalizationContext: ['groups' => ['user:write']]
)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user:me', 'proprietaire:list', 'concierge:list'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user:me', 'proprietaire:list', 'concierge:list', 'appartement:detail'])]
    private ?string $email = null;

    #[ORM\Column]
    #[Ignore]
    private array $roles = [];

    #[ORM\Column]
    #[Ignore]
    private ?string $password = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[MaxDepth(1)]
    #[ApiProperty(readableLink: true)]
    #[Groups(['user:me', 'proprietaire:detail', 'concierge:detail'])]
    private ?Conciergerie $conciergerie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:me',
        'conciergerie:detail','proprietaire:list', 'concierge:list', 'appartement:detail', 'appartement:list'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:me',
        'conciergerie:detail', 'proprietaire:list', 'concierge:list', 'appartement:detail', 'appartement:list'])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:me',
        'conciergerie:detail', 'proprietaire:list', 'concierge:list', 'appartement:detail'])]
    private ?string $telephone = null;

    #[ORM\Column(length: 255)]
    #[Groups(['user:read', 'user:me', 'conciergerie:detail', 'proprietaire:list', 'concierge:list'])]
    private ?string $typeUser;   

    #[ORM\OneToOne(inversedBy: 'user', targetEntity: Proprietaire::class)]
    #[Groups(['user:me'])]
    private ?Proprietaire $proprietaire = null;

    #[ORM\OneToOne(inversedBy: 'user', targetEntity: Concierge::class)]
    #[Groups(['user:me'])]
    private ?Concierge $concierge = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function __serialize(): array
    {
        $data = (array) $this;
        $data["\0".self::class."\0password"] = hash('crc32c', $this->password);
        return $data;
    }

    #[\Deprecated]
    public function eraseCredentials(): void {}

    public function getConciergerie(): ?Conciergerie
    {
        return $this->conciergerie;
    }

    public function setConciergerie(?Conciergerie $conciergerie): static
    {
        $this->conciergerie = $conciergerie;
        return $this;
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

    public function getTypeUser() : string
    {
        return $this->typeUser;   
    }

    public function setType(string $typeUser): static 
    {
        $this->typeUser = $typeUser;

        return $this;
    }
}
