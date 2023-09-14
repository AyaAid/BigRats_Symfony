<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Form\ExpensesFormType;
use App\Service\CreateExpenseService;
use App\Service\GetExpensesConcernedUserService;
use App\Service\GetTableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseController extends AbstractController
{
    private $createExpenseService;
    private $GetExpensesConcernedUserService;
    private $GetTableService;

    public function __construct(CreateExpenseService $createExpenseService, GetExpensesConcernedUserService $GetExpensesConcernedUserService, GetTableService $GetTableService)
    {
        $this->createExpenseService = $createExpenseService;
        $this->GetExpensesConcernedUserService = $GetExpensesConcernedUserService;
        $this->GetTableService = $GetTableService;
    }

    #[Route(path: '/tricount/{tricountId}/new-expenses', name: 'app_tricount_expenses')]
    public function spendForm(Request $request, string $tricountId)
    {
        $expense = new Expenses();

        $form = $this->createForm(ExpensesFormType::class, $expense);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->createExpenseService->createExpense($data, $tricountId);
            return $this->redirectToRoute('home_page');
        }

        return $this->render('expenses.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}