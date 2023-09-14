<?php

namespace App\Controller;

use App\Form\ExpensesFormType;
use App\Service\CreateExpenseService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ExpenseController extends AbstractController
{
    private $createExpenseService;

    public function __construct(CreateExpenseService $createExpenseService)
    {
        $this->createExpenseService = $createExpenseService;
    }

    #[Route(path: '/tricount/{tricountId}/expenses', name: 'app_tricount_expenses')]
    public function spendForm(Request $request, string $tricountId)
    {
        $form = $this->createForm(ExpensesFormType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->createExpenseService->createExpense($data, $request, $tricountId);
            return $this->redirectToRoute('home_page');
        }

        return $this->render('expenses.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}