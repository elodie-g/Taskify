<?php

namespace App\Controller;

use Exception;
use App\Entity\Task;
use App\Service\TaskService;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TaskService $taskService;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->taskService = new TaskService($entityManager);
    }

    /**
     * @Route("/api/task/create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);

        $task = $this->taskService->buildTask(new Task(), $payload);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $this->json([
            'message' => 'Task added in the database',
            'taskId' => $task->getId(),
        ]);
    }

    /**
     * @Route("/api/task/edit/{id}", methods={"PATCH"})
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);

        try {
            $task = $this->taskService->buildTask(
                $this->taskService->getById($id),
                $payload
            );

            $this->entityManager->flush();

            return $this->json([
                'message' => 'Task edited in the database',
                'taskId' => $task->getId(),
            ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/api/task/delete/{id}", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        try {
            $task = $this->taskService->getById($id);

            $this->entityManager->remove($task);
            $this->entityManager->flush();

            return $this->json([
                'message' => 'Task removed from the database'
            ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * @Route("/api/tasks", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $tasks = SerializerUtils::serializeWithCircularReference(
            $this->taskService->getAll()
        );

        return $this->json([
            'message' => 'Tasks retrieved from the database',
            'tasks' => $tasks
        ]);
    }
}
