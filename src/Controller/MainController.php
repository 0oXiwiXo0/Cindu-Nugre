<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'main_')]
final class MainController extends AbstractController
{
    #[Route('', name: 'home', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('apropos', name: 'apropos', methods: ['GET'])]
    public function apropos(): Response
    {
        return $this->render('main/aPropos.html.twig');
    }

    #[Route('contact', name: 'contact', methods: ['GET'])]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }
}
