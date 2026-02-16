<?php

namespace App\Doctrine;

use ApiPlatform\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Proprietaire;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bundle\SecurityBundle\Security;

final class ProprietaireExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function applyToCollection(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        ?\ApiPlatform\Metadata\Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($qb, $resourceClass);
    }

    public function applyToItem(
        QueryBuilder $qb,
        QueryNameGeneratorInterface $queryNameGenerator,
        string $resourceClass,
        array $identifiers,
        ?\ApiPlatform\Metadata\Operation $operation = null,
        array $context = []
    ): void {
        $this->addWhere($qb, $resourceClass);
    }

    private function addWhere(QueryBuilder $qb, string $resourceClass): void
    {
        if ($resourceClass !== Proprietaire::class) {
            return;
        }

        $user = $this->security->getUser();

        if (!$user) {
            return;
        }

        $rootAlias = $qb->getRootAliases()[0];

        $qb
            ->join($rootAlias . '.user', 'u')
            ->join('u.conciergerie', 'c')
            ->andWhere('c = :conciergerie')
            ->setParameter('conciergerie', $user->getConciergerie());
    }
}