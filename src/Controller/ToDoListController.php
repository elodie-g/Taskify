<?php

namespace App\Controller;

use App\Service\TaskService;
use App\Service\ToDoListService;
use App\Utils\SerializerUtils;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ToDoListController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private ToDoListService $toDoListService;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
        $this->toDoListService = new ToDoListService($entityManager);
    }

    /**
     * @Route("/api/todo", methods={"GET"})
     */
    public function getAll(): JsonResponse
    {
        $tasks = SerializerUtils::serializeWithCircularReference(
            $this->toDoListService->getAll()
        );

        return $this->json([
            'message' => 'Tasks retrieved from the database',
            'tasks' => $tasks
        ]);
    }

    /**
     * @Route("/api/todo/status/{id}", methods={"PATCH"})
     */
    public function setToDoItemStatus(Request $request, int $id): JsonResponse
    {
        $payload = json_decode($request->getContent(), false);

        try {
            $toDoItem = $this->toDoListService->getById($id);
            $toDoItem->setIsDone($payload->is_done);

            $this->entityManager->flush();

            return $this->json([
                'message' => 'Item edited in the database',
                'item' => $toDoItem->getId(),
            ]);
        } catch (Exception $e) {
            return $this->json([
                'error' => $e->getMessage()
            ]);
        }
    }
}
