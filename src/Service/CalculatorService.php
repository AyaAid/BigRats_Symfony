<?php

namespace App\Service;

use App\Entity\Expenses;
use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;

class CalculatorService
{
private EntityManagerInterface $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;
}

public function calculateDebts(Tricounts $tricount): array
{

$expenses = $this->entityManager->getRepository(Expenses::class)->findBy(['tricount' => $tricount]);


$balances = [];


foreach ($expenses as $expense) {
$totalAmount = $expense->getValue();
$user = $expense->getUser();


$share = $totalAmount / count($tricount->getUser());

if (!isset($balances[$user->getId()])) {
$balances[$user->getId()] = 0;
}
$balances[$user->getId()] += $share;
}

return $balances;
}
}
