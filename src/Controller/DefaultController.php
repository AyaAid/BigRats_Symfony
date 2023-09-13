<?php

namespace App\Controller;

use App\Entity\Tricounts;
use App\Form\CreateTricountsType;
use App\Service\CreateTricountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class DefaultController extends AbstractController
{

    private Environment $twig;

    private CreateTricountService $createTricountService;

    public function __construct(Environment $twig, CreateTricountService $createTricountService)
    {
        $this->twig = $twig;
        $this->createTricountService = $createTricountService;
    }

    #[Route('/create-tricounts', name: 'createtricounts')]
    public function createTricounts(Request $request)
    {
        $form = $this->createForm(CreateTricountsType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $this->createTricountService->createTricount($data, $request);
            return $this->redirectToRoute('app_login');
        }

        $content = $this->render('create_tricount_modal.html.twig', [
            'form' => $form->createView(),
        ]);

        return new Response($content);
    }
}