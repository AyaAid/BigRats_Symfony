<?php

namespace App\Service;

use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class GetTricountsByUserId
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getTable($table, $user)
    {
        return $this->entityManager->getRepository($table)->find($user)->getTricounts();
    }
}