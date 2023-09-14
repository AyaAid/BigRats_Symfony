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

    public function editExpense(Expenses $expense, Expenses $formData): bool
    {
        // Retrieve the current expense from the database
        $currentExpense = $this->entityManager->getRepository(Expenses::class)->find($expense->getId());

        // Check if the values are different
        if (
            $currentExpense->getTitle() !== $formData->getTitle() ||
            $currentExpense->getValue() !== $formData->getValue()
            // Add more comparisons for other fields if needed
        ) {
            // Update the expense with the new values
            $currentExpense->setTitle($formData->getTitle());
            $currentExpense->setValue($formData->getValue());
            // Update other fields as needed

            // Persist the updated expense in the database
            $this->entityManager->flush();

            return true; // Expense was updated
        }

        return false; // Expense values are the same, no update needed
    }
}
