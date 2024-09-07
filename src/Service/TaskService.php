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
        return $this->entityManager->getRepository(Task::class)->findAll();
    }

    public function getById(int $id)
    {
        $task = $this->entityManager->getRepository(Task::class)->find($id);

        if (!$task) {
            throw new Exception('No product found for id ' . $id);
        }

        return $task;
    }

    public function buildTask(Task $task, $payload)
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