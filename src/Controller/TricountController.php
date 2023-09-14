<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Entity\Expenses;
use App\Entity\Users;
use App\Service\GetTableByIdService;
use App\Service\GetSessionUserService;
use App\Service\GetTricountsByUserId;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TricountController extends AbstractController
{

    private $GetTableByIdService;
    private $GetSessionUserService;
    private $entityManager;


    public function __construct(GetTableByIdService $GetTableByIdService, GetSessionUserService $GetSessionUserService, GetTricountsByUserId $GetTableByUserService, EntityManagerInterface $entityManager)
    {
        $this->GetTableByIdService = $GetTableByIdService;
        $this->GetTableByUserService = $GetTableByUserService;
        $this->GetSessionUserService = $GetSessionUserService;
        $this->entityManager = $entityManager;



    }

    #[Route(path: '/tricount/{tricountId}', name: 'app_tricount')]
    public function index(string $tricountId )
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

        return $this->render('tricount.html.twig', [
            'user_expenses' => $expenses,
            'tricount_user' => $userOfTricount
        ]);

    }
}