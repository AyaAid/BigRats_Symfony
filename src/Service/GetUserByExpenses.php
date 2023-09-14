<?php

namespace App\Service;

use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;

class GetUserByExpenses
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getTable($table, $tricountId)
    {
        return $this->entityManager->getRepository($table)->find($tricountId)->getUser();
    }
}