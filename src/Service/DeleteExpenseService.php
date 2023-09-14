<?php

namespace App\Service;

use App\Entity\Expenses;
use Doctrine\ORM\EntityManagerInterface;

class DeleteExpenseService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function deleteExpense(Expenses $expense)
    {
        $this->entityManager->remove($expense);
        $this->entityManager->flush();
    }
}
