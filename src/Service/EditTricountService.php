<?php
namespace App\Service;

use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;


class EditTricountService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function editTricount(Tricounts $tricount): void
    {
        $this->entityManager->flush();
    }
}
