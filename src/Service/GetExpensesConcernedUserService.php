<?php

namespace App\Service;

use App\Entity\Tricounts;

class GetExpensesConcernedUserService
{

    private $GetTableByIdService;

    public function __construct(GetTableByIdService $GetTableByIdService)
    {
        $this->GetTableByIdService = $GetTableByIdService;
    }

    public function getExpensesConcernedUser($users, $tricountId)
    {
        $tricountArray = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);
        $tricount = reset($tricountArray);

        foreach ($users as $user) {
            if ($tricount->getUser()->contains($user)) {
                $expensesConcernedUser[] = $user;
            }
        }
        return $expensesConcernedUser;
    }
}