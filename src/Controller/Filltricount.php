<?php

namespace App\Controller;

use App\Entity\Tricounts;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class Filltricount extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/filltricount', name: 'filltricount')]
    public function index()
    {
        $tricount = new Tricounts();
        $tricount->setName('stojanovic');
        $tricount->setDescription('le macdo');
        $tricount->setCategory('restaurant');
        $tricount->setDevise('euro');
        $tricount->setNumber('4');



        $this->entityManager->persist($tricount);
        $this->entityManager->flush();

        return $this->render('base.html.twig', [
            'controller_name' => $this->entityManager->getRepository(Tricounts::class)->findAll(),
        ]);
    }}