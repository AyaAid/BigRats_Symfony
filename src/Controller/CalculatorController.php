<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Service\CalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{
    private CalculatorService $calculator;

    public function __construct(CalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    #[Route('/calculator/{tricount}', name: 'calculator')]
    public function showCalcul(Tricounts $tricount): Response
    {
        $usersWithBalances = $this->calculator->calculateDebts($tricount);

        $totalExpenses = 0;
        foreach ($tricount->getExpenses() as $expense) {
            $totalExpenses += $expense->getValue();
        }

        $averageExpense = $totalExpenses / count($usersWithBalances);

        $usersWithBalancesAssociative = [];

        foreach ($usersWithBalances as $userId => $userBalance) {
            $usersWithBalancesAssociative[$userId] = [
                'balance' => $userBalance,
                'netAmount' => $userBalance - $averageExpense,
            ];
        }

        return $this->render('show_calcul.html.twig', [
            'tricount' => $tricount,
            'usersWithBalances' => $usersWithBalancesAssociative,
        ]);
    }
}
