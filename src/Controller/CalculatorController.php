<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Entity\Users;
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
    public function showCalcul(Tricounts $tricount, CalculatorService $calculator): Response
    {
        $usersWithBalances = $calculator->calculateDebts($tricount);

        $users = $this->getDoctrine()->getRepository(Users::class)->findBy(['id' => array_keys($usersWithBalances)]);

        $usersData = [];
        foreach ($users as $user) {
            $usersData[$user->getId()] = $user;
        }

        $totalExpenses = 0;
        foreach ($tricount->getExpenses() as $expense) {
            $totalExpenses += $expense->getValue();
        }

        $averageExpense = $totalExpenses / count($usersWithBalances);

        return $this->render('show_calcul.html.twig', [
            'tricount' => $tricount,
            'usersData' => $usersData,
            'usersWithBalances' => $usersWithBalances,
        ]);
    }
}
