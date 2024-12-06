<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TaskService
{
    private EntityManager $entityManager;
    private UserService $userService;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->userService = new UserService($entityManager);
    }

    public function getAll()
    {
        // $connection = $this->entityManager->getConnection();

        // $sql = "SELECT task.label, task.duration, task.frequency, worker.name, GROUP_CONCAT(day.name SEPARATOR ', ' ) as days
        //         FROM task
        //         INNER JOIN worker ON task.assigned_to_id = worker.id
        //         INNER JOIN task_day ON task.id = task_day.task_id
        //         INNER JOIN day ON task_day.day_id = day.id
        //         GROUP BY task.id";

        // $sql = "SELECT label, duration, frequency, type, value, ";

        // $sql = "SELECT task.label, task.duration, task.frequency, username as worker_name
        //         FROM task
        //         INNER JOIN user ON task.assigned_to_id = user.id
        //         INNER JOIN day ON task_day.day_id = day.id";

        // $stmt = $connection->prepare($sql);
        // $stmt = $stmt->executeQuery();
        // return $stmt->fetchAllAssociative();

        return $this->entityManager->getRepository(Task::class)->findAll();
    }

    public function getAllByWorkerName(string $name)
    {
        $worker = $this->userService->getByName($name);

        return $this->entityManager->getRepository(Task::class)->findBy([
            'assigned_to' => $worker->getId()
        ]);
    }

    public function getById(int $id)
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw new Exception('No product found for id ' . $id);
        }

        return $task;
    }

    public function buildTask(Task $task, object $payload)
    {
        $task->setLabel($payload->label);
        $task->setDuration($payload->duration);
        $task->setFrequency($payload->frequency);

        $worker = $this->userService->getById($payload->workerId);
        $task->setAssignedTo($worker);

        return $task;
    }
}