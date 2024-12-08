<?php

namespace App\Service;

use App\Entity\ToDoList;
use App\Helper\ToDoListHelper;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class ToDoListService
{
    private EntityManager $entityManager;
    private ToDoListHelper $toDoListHelper;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->toDoListHelper = new ToDoListHelper();
    }

    public function getAll(array $filters)
    {
        $filteredQuery = $this->toDoListHelper->buildFilteredQuery($filters);
        return $this->entityManager->getRepository(ToDoList::class)->findAllBy($filteredQuery);
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