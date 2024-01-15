<?php

namespace App\Repository;

use App\Entity\CodePostale;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CodePostale>
 *
 * @method CodePostale|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodePostale|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodePostale[]    findAll()
 * @method CodePostale[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodePostaleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodePostale::class);
    }

//    /**
//     * @return CodePostale[] Returns an array of CodePostale objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CodePostale
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
