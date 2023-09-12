<?php

namespace App\Repository;

use App\Entity\Tricounts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tricounts>
 *
 * @method Tricounts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tricounts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tricounts[]    findAll()
 * @method Tricounts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TricountsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tricounts::class);
    }

//    /**
//     * @return Tricounts[] Returns an array of Tricounts objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Tricounts
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
