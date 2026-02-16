<?php

namespace App\Repository;

use App\Entity\Proprietaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Proprietaire>
 */
class ProprietaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Proprietaire::class);
    }

       /**
        * @return Proprietaire[] Returns an array of Proprietaire objects
        */
       public function findByConciergerie($id_conciergerie): array
       {
           return $this->createQueryBuilder('p')
                ->join("p.user", 'u' )
                -join("u.conciergerie", 'c')
               ->andWhere('c.id = :val')
               ->setParameter('val', $id_conciergerie)
               ->orderBy('p.id', 'ASC')
               ->setMaxResults(10)
               ->getQuery()
               ->getResult()
           ;
       }

    //    /**
    //     * @return Proprietaire[] Returns an array of Proprietaire objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Proprietaire
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
