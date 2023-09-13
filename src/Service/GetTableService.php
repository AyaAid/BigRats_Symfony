<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class GetTableService
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getTable($table)
    {
        return $this->entityManager->getRepository($table)->findAll();
    }
}