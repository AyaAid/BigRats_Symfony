<?php

namespace App\Controller;

use App\Entity\Users;
use App\Service\GetSessionUserService;
use App\Service\GetTricountsByUserId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private $GetTableByUserService;
    private $GetSessionUserService;


    public function __construct(GetTricountsByUserId $GetTableByUserService, GetSessionUserService $GetSessionUserService)
    {
        $this->GetTableByUserService = $GetTableByUserService;
        $this->GetSessionUserService = $GetSessionUserService;
    }

    #[Route("/", name: "home_page")]
    public function index()
    {
        return $this->render('home_page.html.twig', [
            'title' => 'Home Page',
            'user_tricounts' => $this->GetTableByUserService->getTable(Users::class, $this->GetSessionUserService->getUser())
        ]);
    }
}