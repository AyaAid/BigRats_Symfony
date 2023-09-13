<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class GetTableByIdService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getTable($table, $id)
    {
        return $this->entityManager->getRepository($table)->findBy(['id' => $id]);
    }
}