<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Expenses;

class EditExpensesService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function editExpense(Expenses $expense): void
    {
        $this->entityManager->flush();
    }
}
