<?php


namespace App\Controller;

use App\Entity\Tricounts;
use App\Entity\Users;
use App\Service\GetSessionUserService;
use App\Service\GetTricountsByUserId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    private $GetTableByUserService;
    private $GetSessionUserService;

    public function __construct(GetTricountsByUserId $GetTableByUserService, GetSessionUserService $GetSessionUserService)
    {
        $this->GetTableByUserService = $GetTableByUserService;
        $this->GetSessionUserService = $GetSessionUserService;
    }

    #[Route("/authentication", name:"controller_test")]
    public function index(): Response
    {
        return $this->render('/static/authentication.html.twig', ['name' => 'test']);
    }

    #[Route("/list-tricounts", name:"listtricounts")]
    public function listtricounts(): Response
    {
        return $this->render('list-tricounts.html.twig', ['tricountslist' => $this->GetTableByUserService->getTable(Users::class, $this->GetSessionUserService->getUser())]);
    }

    #[Route("/error_404", name:"error_404")]
    public function error(): Response
    {
        return $this->render('/static/error_404.html.twig', ['name' => 'test']);
    }

    #[Route("/login_static", name:"login_static")]
    public function static(): Response
    {
        return $this->render('/static/login_static.html.twig', ['name' => 'test']);
    }
}