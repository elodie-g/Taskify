<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getById(int $id)
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function getByName(string $name)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['username' => $name]);
    }
}