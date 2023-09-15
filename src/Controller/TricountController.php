<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Form\TricountEditType;
use App\Service\CalculatorService;
use App\Service\EditTricountService;
use App\Service\GetTableByIdService;
use App\Service\LeaveTricountsService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Snappy\Pdf;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class TricountController extends AbstractController
{

    private $GetTableByIdService;
    private $leaveTricountsService;
    private $entityManager;
    private $calculator;

    public function __construct(GetTableByIdService $GetTableByIdService, LeaveTricountsService $leaveTricountsService, EditTricountService $editTricountService, EntityManagerInterface $entityManager, CalculatorService $calculator, Pdf $pdf)
    {
        $this->GetTableByIdService = $GetTableByIdService;
        $this->leaveTricountsService = $leaveTricountsService;
        $this->editTricountService = $editTricountService;
        $this->entityManager = $entityManager;
        $this->pdf = $pdf;
        $this->calculator = $calculator;
    }

    #[Route(path: '/tricount/{tricountId}', name: 'app_tricount')]
    public function index(string $tricountId)
    {
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId)[0];

        $expenses = $this->entityManager->createQuery('SELECT e FROM App\Entity\Expenses e WHERE e.tricount = :tricount')
            ->setParameter('tricount', $tricount)
            ->getResult();

        $userOfTricount = $this->entityManager->createQuery('SELECT u FROM App\Entity\Expenses u WHERE u.user = :user')
            ->setParameter('user', $tricount)
            ->getResult();

        if (!$tricount) {
            return $this->render('page_not_found.twig', ['message' => 'Le tricount n\'existe pas']);
        }

        $usersWithBalances = $tricount->getUsersWithBalances();

        $totalExpenses = 0;
        foreach ($tricount->getExpenses() as $expense) {
            $totalExpenses += $expense->getValue();
        }

        $averageExpense = $totalExpenses / count($usersWithBalances);

        foreach ($usersWithBalances as &$userWithBalance) {
            $userBalance = $userWithBalance['balance'];
            $userWithBalance['netAmount'] = $userBalance - $averageExpense;

        }

        return $this->render('tricount.html.twig', ['tricountArray' => $tricount,
            'user_expenses' => $expenses,
            'usersWithBalances' => $usersWithBalances,
            'tricount_user' => $userOfTricount,
            'totalExpenses' => $totalExpenses]);
    }

    #[Route(path: '/tricount/{tricountId}/quit', name: 'app_quit_tricount', methods: ['POST'])]
    public function quitTricount(string $tricountId, LeaveTricountsService $leaveTricountsService, Request $request): Response
    {
        $tricounts = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);

        if (empty($tricounts)) {
            throw $this->createNotFoundException('Le tricount n\'existe pas');
        }

        $tricount = reset($tricounts);

        $user = $this->getUser();

        if ($request->isMethod('POST')) {
            $this->leaveTricountsService->leaveTricount($user, $tricount);
            return $this->redirectToRoute('home_page');
        }

        return $this->render('quit_tricount.html.twig', ['tricount' => $tricount]);
    }

    #[Route(path: '/tricount/{tricountId}/edit', name: 'app_edit_tricount')]
    public function editTricount(Request $request, Tricounts $tricountId): Response
    {
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);


        if (empty($tricount)) {
            throw $this->createNotFoundException('Le tricount n\'existe pas');
        }

        $tricount = reset($tricount);
        $form = $this->createForm(TricountEditType::class, $tricount);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->editTricountService->editTricount($tricount);
            return $this->redirectToRoute('app_tricount', ['tricountId' => $tricount->getId()]);
        }

        return $this->render('edit_tricount.html.twig', [
            'tricount' => $tricount,
            'form' => $form->createView(),
        ]);
    }
    #[Route(path: '/tricount/{tricountId}/pdf', name: 'app_pdf_tricount')]
    public function pdfTricount(string $tricountId, Pdf $pdf) {
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);

        if (empty($tricount)) {
            throw $this->createNotFoundException('Le tricount n\'existe pas');
        }

        $tricountArray = reset($tricount);

        $expenses = $this->entityManager->createQuery('SELECT e FROM App\Entity\Expenses e WHERE e.tricount = :tricount')
            ->setParameter('tricount', $tricountArray)
            ->getResult();

        $html = $this->renderView('pdf.html.twig', [
            'tricountArray' => $tricountArray,
            'user_expenses' => $expenses,
        ]);

        $filename = sprintf('tricount-%s.pdf', date('Y-m-d'));

        return new Response(
            $this->pdf->getOutputFromHtml($html),
            200,
            [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
            ]
        );
    }




}