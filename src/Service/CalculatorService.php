<?php

namespace App\Service;

use App\Entity\Expenses;
use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CalculatorService extends AbstractController
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
            $usersCollection = $expense->getUser();
            foreach ($usersCollection as $user) {
                $userId = $user->getId();

                if (!isset($balances[$userId])) {
                    $balances[$userId] = 0;
                }

                $share = $totalAmount / count($usersCollection);

                $balances[$userId] += $share;
            }
        }

        return $balances;
    }
}



