<?php

namespace App\Service;

use App\Entity\Tricounts;
use App\Form\CreateTricountsType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CreateTricountService
{
    private $formFactory;

    private $requestStack;

    private $entityManager;

    private $urlGenerator;

    private $security;

    public function __construct(FormFactoryInterface $formFactory, RequestStack $requestStack, EntityManagerInterface
    $entityManager, UrlGeneratorInterface $urlGenerator, Security $security)
    {
        $this->formFactory = $formFactory;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->security = $security;
    }

    public function createTricount($data, Request $request)
    {
            $name = $data->getName();
            $description = $data->getDescription();
            $category = $data->getCategory();
            $user = $this->security->getUser();

            $tricount = new Tricounts();
            $tricount->setName($name);
            $tricount->setDescription($description);
            $tricount->setCategory($category);
            $tricount->addUser($user);
            $this->entityManager->persist($tricount);
            $this->entityManager->flush();
    }
}
