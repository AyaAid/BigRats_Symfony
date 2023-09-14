<?php

namespace App\Service;

use App\Entity\Tricounts;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;

class LeaveTricountsService
{
private EntityManagerInterface $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;
}

    public function leaveTricount(Users $user, Tricounts $tricount): void
    {
        if ($tricount->countUsers() > 0) {
            $tricount->removeUser($user);
            if ($tricount->countUsers() === 0) {
                $this->removeEmptyTricount($tricount);
            }

            $this->entityManager->flush();
        }
    }

    private function removeEmptyTricount(Tricounts $tricount): void
    {
        $this->entityManager->remove($tricount);
        $this->entityManager->flush();
    }

}
