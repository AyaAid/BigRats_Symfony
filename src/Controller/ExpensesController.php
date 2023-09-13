<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Form\ExpensesFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ExpensesController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/expenses', name: 'expenses')]
    public function SpendForm(Request $request): Response
    {
        $expenses = new Expenses();
        $expenses->setCreatedAt(new \DateTimeImmutable());



        $form = $this->createForm(ExpensesFormType::class, $expenses);
        $form->add('save', SubmitType::class, [
            'label' => 'Spend',
        ]);
        $form->handleRequest($request);



        if ( $form->isSubmitted() && $form->isValid()) {

            $this->entityManager->persist($expenses);
            $this->entityManager->flush();
        }


        return $this->render('expenses.html.twig', [
            'form' => $form->createView(),

        ]);
    }
}