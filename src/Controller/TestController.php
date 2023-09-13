<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    #[Route("/authentication", name:"controller_test")]
    public function index(): Response
    {
        return $this->render('authentication.html.twig', ['name' => 'test']);
    }
}