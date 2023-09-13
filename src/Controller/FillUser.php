<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Users;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class FillUser extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/article', name: 'article')]
    public function index()
    {
        $user = new Users();
        $user->setEmail('markostojanovicsefaitcacadessus');
        $user->setFirstname('Marko');
        $user->setLastName('Stojanovic');
        $user->setPassword('Stojanovic');
        $user->setRole('cacaMaster');
        $user->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->render('index.html.twig', [
            'controller_name' => $this->entityManager->getRepository(Article::class)->findAll(),
        ]);
    }
}