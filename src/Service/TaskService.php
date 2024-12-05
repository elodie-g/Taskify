<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class TaskService
{
    private EntityManager $entityManager;
    private DayService $dayService;
    private WorkerService $workerService;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->dayService = new DayService($entityManager);
        $this->workerService = new WorkerService($entityManager);
    }

    public function getAll()
    {
        $connection = $this->entityManager->getConnection();

        // $sql = "SELECT task.label, task.duration, task.frequency, worker.name, GROUP_CONCAT(day.name SEPARATOR ', ' ) as days
        //         FROM task
        //         INNER JOIN worker ON task.assigned_to_id = worker.id
        //         INNER JOIN task_day ON task.id = task_day.task_id
        //         INNER JOIN day ON task_day.day_id = day.id
        //         GROUP BY task.id";

        $sql = "SELECT task.label, task.duration, task.frequency, worker.name as worker_name, day.name as day_name
                FROM task
                INNER JOIN worker ON task.assigned_to_id = worker.id
                INNER JOIN task_day ON task.id = task_day.task_id
                INNER JOIN day ON task_day.day_id = day.id";

        $stmt = $connection->prepare($sql);
        $stmt = $stmt->executeQuery();
        return $stmt->fetchAllAssociative();

        // return $this->entityManager->getRepository(Task::class)->findAll();
    }

    public function getAllByWorkerName(string $name)
    {
        $worker = $this->workerService->getByName($name);

        return $this->entityManager->getRepository(Task::class)->findBy([
            'assigned_to' => $worker->getId()
        ]);
    }

    public function getAllByDay(string $day)
    {
        $day = $this->dayService->getAllByName($day);

        return $this->entityManager->getRepository(Task::class)->findBy([
            'scheduled' => $day->getId()
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
        $task->getScheduled()->clear();

        $days = $this->dayService->getByIds($payload->days);
        
        foreach ($days as $day) {
            $task->addScheduled($day);
        }

        $worker = $this->workerService->getById($payload->workerId);
        $task->setAssignedTo($worker);

        return $task;
    }
}