<?php

namespace App\Repository;

use App\Entity\DoneTasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DoneTasks>
 *
 * @method DoneTasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoneTasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoneTasks[]    findAll()
 * @method DoneTasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoneTasksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DoneTasks::class);
    }

//    /**
//     * @return DoneTasks[] Returns an array of DoneTasks objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DoneTasks
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
