<?php

namespace App\Controller;

use App\Entity\Expenses;
use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FillExpenses extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/fillexpenses', name: 'eillexpenses')]
    public function index()
    {
        $expenses = new Expenses();
        $expenses->setTricount('1');
        $expenses->setTitle('macdo');
        $expenses->setValue('20');



        $this->entityManager->persist($expenses);
        $this->entityManager->flush();

        return $this->render('base.html.twig', [
            'controller_name' => $this->entityManager->getRepository(Tricounts::class)->findAll(),
        ]);
    }}