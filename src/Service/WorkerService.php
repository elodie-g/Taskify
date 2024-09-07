<?php

namespace App\Service;

use App\Entity\Worker;
use Doctrine\ORM\EntityManagerInterface;

class WorkerService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getById(int $id)
    {
        return $this->entityManager->getRepository(Worker::class)->find($id);
    }

    public function getByName(string $name)
    {
        return $this->entityManager->getRepository(Worker::class)->findOneBy(['name' => $name]);
    }
}