<?php

namespace App\Service;

use App\Entity\Expenses;
use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseService
{

    private $security;

    private $entityManager;

    private $GetTableByIdService;

    public function __construct(EntityManagerInterface $entityManager, Security $security, GetTableByIdService $GetTableByIdService)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
        $this->GetTableByIdService = $GetTableByIdService;
    }

    public function createExpense($data, Request $request, $tricountId)
    {
        $title = $data->getTitle();
        $value = $data->getValue();
        $user = $this->security->getUser();
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);

        $expense = new Expenses();
        $expense->setTitle($title);
        $expense->setValue($value);
        $expense->setTricount($tricount[0]);
        $expense->setCreatedAt(new \DateTimeImmutable());
        $expense->setUser($user);

        $this->entityManager->persist($expense);
        $this->entityManager->flush();
    }
}