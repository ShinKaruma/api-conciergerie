<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Doctrine\Orm\Filter\ExactFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\QueryParameter;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use App\Repository\ConciergeRepository;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConciergeRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => ['concierge:detail', 'user:me']]),
        new GetCollection(
            normalizationContext: ['groups' => ['concierge:list']],
            parameters: [
                'userId' => new QueryParameter(
                    property: 'user',
                    filter: new ExactFilter()
                ),
                'conciergerieId' => new QueryParameter(
                    property: 'user.conciergerie',
                    filter: new ExactFilter()
                ),
            ]
        ),
        
    ],
    denormalizationContext: ['groups' => ['write']],
)]
class Concierge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups([
        'user:me',
        'concierge:detail',
        'concierge:list'
    
    ])]
    private ?int $id = null;

    // Relation plus lisible
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: 'id_user', nullable: false)]
    #[ApiProperty(readableLink: true)]
    #[Groups(['concierge:detail', 'concierge:list'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): static
    {
        $this->user = $user;
        return $this;
    }
}
