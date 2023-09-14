<?php

namespace App\Service;

use App\Entity\Expenses;
use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;

class CalculatorService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculateDebts(Tricounts $tricount): array
    {
        $expenses = $this->entityManager->getRepository(Expenses::class)->findBy(['tricount' => $tricount]);

        $balances = [];

        foreach ($expenses as $expense) {
            $totalAmount = $expense->getValue();
            $expenseUsers = $expense->getConcernedUsers();

            if (count($expenseUsers) === 0) {
                continue;
            }

            $share = $totalAmount / count($expenseUsers);

            foreach ($expenseUsers as $user) {
                $userId = $user->getId();
                if (!isset($balances[$userId])) {
                    $balances[$userId] = 0;
                }
                $balances[$userId] += $share;
            }
        }

        return $balances;
    }
}
