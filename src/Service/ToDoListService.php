<?php

namespace App\Service;

use App\Entity\ToDoList;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ToDoListService
{
    private EntityManager $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getAll()
    {
        return $this->entityManager->getRepository(ToDoList::class)->findAll();
    }

    public function getById(int $id)
    {
        $toDoItem = $this->entityManager->getRepository(ToDoList::class)->find($id);

        if (!$toDoItem) {
            throw new Exception('No item found for id ' . $id);
        }

        return $toDoItem;
    }
}