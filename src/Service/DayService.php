<?php

namespace App\Service;

use App\Entity\Day;
use Doctrine\ORM\EntityManagerInterface;

class DayService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getByIds(array $ids)
    {
        return $this->entityManager->getRepository(Day::class)->findBy(array('id' => $ids));
    }
}