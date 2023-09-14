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

    public function editTricount(Tricounts $tricount, array $data): void
    {
        $tricount->setName($data['name']);
        $tricount->setDescription($data['description']);
        $tricount->setCategory($data['category']);
        $tricount->setDevise($data['devise']);

        $this->entityManager->flush();
    }
}
