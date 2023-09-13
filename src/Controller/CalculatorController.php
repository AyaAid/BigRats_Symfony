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
        // Obtenez la liste des utilisateurs avec leurs soldes
        $usersWithBalances = $tricount->getUsersWithBalances();

        // Calculez le montant total des dépenses
        $totalExpenses = 0;
        foreach ($tricount->getExpenses() as $expense) {
            $totalExpenses += $expense->getValue();
        }

        // Calculez le montant moyen par utilisateur
        $averageExpense = $totalExpenses / count($usersWithBalances);

        // Effectuez la répartition des dépenses
        foreach ($usersWithBalances as &$userWithBalance) {
            $userBalance = $userWithBalance['balance'];
            $userWithBalance['netAmount'] = $userBalance - $averageExpense;
        }

        return $this->render('show_calcul.html.twig', [
            'tricount' => $tricount,
            'usersWithBalances' => $usersWithBalances,
        ]);
    }
}