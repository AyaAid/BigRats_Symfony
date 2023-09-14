<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class ExpenseClick
{
#[Route(path: '/expense/{expenseId}', name: 'app_tricount')]
    public function index(string $tricountId )
    {
        return $this->render('tricount.html.twig', [
            'controller_name' => 'ExpenseClick',
        ]);
    }
}