<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Service\GetTableByIdService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TricountController extends AbstractController
{

    private $GetTableByIdService;

    public function __construct(GetTableByIdService $GetTableByIdService)
    {
        $this->GetTableByIdService = $GetTableByIdService;
    }

    #[Route(path: '/tricount/{tricountId}', name: 'app_tricount')]
    public function index(string $tricountId)
    {
        $tricount = $this->GetTableByIdService->getTable(Tricounts::class, $tricountId);

        if (!$tricount) {
            return $this->render('page_not_found.twig', ['message' => 'Le tricount n\'existe pas']);
        }

        return $this->render('tricount.html.twig', ['tricountArray' => $tricount]);
    }
}