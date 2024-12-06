<?php

namespace App\Repository;

use App\Entity\ToDoList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToDoList>
 *
 * @method ToDoList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToDoList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToDoList[]    findAll()
 * @method ToDoList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToDoListRepository extends ServiceEntityRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager, ManagerRegistry $registry)
    {
        parent::__construct($registry, ToDoList::class);
        $this->entityManager = $entityManager;
    }

    public function findAll()
    {
        $connection = $this->entityManager->getConnection();

        $sql = "SELECT to_do_list.id, to_do_list.date, to_do_list.is_done,
                task.label, task.duration, task.frequency, task.type, task.value,                
                user.username
                FROM to_do_list
                INNER JOIN task ON to_do_list.task_id = task.id
                INNER JOIN user ON task.assigned_to_id = user.id";

        $stmt = $connection->prepare($sql);
        $stmt = $stmt->executeQuery();
        return $stmt->fetchAllAssociative();
    }

//    /**
//     * @return ToDoList[] Returns an array of ToDoList objects
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

//    public function findOneBySomeField($value): ?ToDoList
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
