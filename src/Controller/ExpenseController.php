<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Form\CreateTricountsType;
use App\Form\EditExpensesType;
use App\Form\ExpensesFormType;
use App\Service\CreateExpenseService;
use App\Service\DeleteExpenseService;
use App\Service\EditExpensesService;
use App\Service\GetExpensesConcernedUserService;
use App\Service\GetTableByIdService;
use App\Service\GetTableService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseController extends AbstractController
{
    private $createExpenseService;
    private $GetExpensesConcernedUserService;
    private $GetTableService;
    private $GetTableByIdService;
    private $DeleteExpenseService;

    public function __construct(CreateExpenseService $createExpenseService, GetExpensesConcernedUserService $GetExpensesConcernedUserService, GetTableService $GetTableService, DeleteExpenseService $DeleteExpensesService, GetTableByIdService $GetTableByIdService, EditExpensesService $editExpensesService)
    {
        $this->createExpenseService = $createExpenseService;
        $this->GetExpensesConcernedUserService = $GetExpensesConcernedUserService;
        $this->GetTableService = $GetTableService;
        $this->DeleteExpenseService = $DeleteExpensesService;
        $this->GetTableByIdService = $GetTableByIdService;
        $this->editExpensesService = $editExpensesService;
    }

    #[Route(path: '/tricount/{tricountId}/new-expenses', name: 'app_tricount_expenses')]
    public function spendForm(Request $request, string $tricountId)
    {
        $expense = new Expenses();

        $form = $this->createForm(ExpensesFormType::class, $expense, [
            'tricountId' => $tricountId,
        ]);

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

    #[Route(path: '/expenses/{expensesId}/delete', name: 'app_expenses_delete')]
    public function deleteExpenses(string $expensesId)
    {
        $this->DeleteExpenseService->deleteExpense($this->GetTableByIdService->getTable(Expenses::class, $expensesId)[0]);

        return $this->redirectToRoute('home_page');
    }

    #[Route(path: '/expenses/{expensesId}/edit', name: 'app_expenses_edit')]
    public function edit(Request $request, $expensesId) {
        $form = $this->createForm(EditExpensesType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->editExpensesService->editExpense($this->GetTableByIdService->getTable(Expenses::class, $expensesId)[0], $data);
            return $this->redirectToRoute('app_login');
        }

        $content = $this->render('create_tricount_modal.html.twig', [
            'form' => $form->createView(),
        ]);

        return $content;
    }
}