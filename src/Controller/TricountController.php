<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Service\EditTricountService;
use App\Service\GetTableByIdService;
use App\Service\LeaveTricountsService;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(GetTableByIdService $GetTableByIdService, LeaveTricountsService $leaveTricountsService, EditTricountService $editTricountService, EntityManagerInterface $entityManager)
    {
        $this->GetTableByIdService = $GetTableByIdService;
        $this->leaveTricountsService = $leaveTricountsService;
        $this->editTricountService = $editTricountService;
        $this->entityManager = $entityManager;
    }

    #[Route(path: '/tricount/{tricountId}', name: 'app_tricount')]
    public function index(string $tricountId)
    {
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);

        $expenses = $this->entityManager->createQuery('SELECT e FROM App\Entity\Expenses e WHERE e.tricount = :tricount')
            ->setParameter('tricount', $tricount)
            ->getResult();

        $userOfTricount = $this->entityManager->createQuery('SELECT u FROM App\Entity\Expenses u WHERE u.user = :user')
            ->setParameter('user', $tricount)
            ->getResult();

        if (!$tricount) {
            return $this->render('page_not_found.twig', ['message' => 'Le tricount n\'existe pas']);
        }

        return $this->render('tricount.html.twig', ['tricountArray' => $tricount[0],
            'user_expenses' => $expenses,
            'tricount_user' => $userOfTricount]);
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
    public function editTricount(Request $request, string $tricountId): Response
    {
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);


        if (empty($tricount)) {
            throw $this->createNotFoundException('Le tricount n\'existe pas');
        }

        $tricount = reset($tricount);
        $form = $this->createForm(TricountEditType::class, $tricount);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->editTricountService->editTricount($tricount, $data);
            return $this->redirectToRoute('app_tricount', ['tricountId' => $tricount->getId()]);
        }

        return $this->render('edit_tricount.html.twig', [
            'tricount' => $tricount,
            'form' => $form->createView(),
        ]);
    }
}