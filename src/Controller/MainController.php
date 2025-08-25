<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig');
    }

    #[Route('/aPropos', name: 'main_aPropos')]
    public function aPropos(): Response
    {
        return $this->render('main/aPropos.html.twig');
    }

    #[Route('/contact', name: 'main_contact')]
    public function contact(): Response
    {
        return $this->render('main/contact.html.twig');
    }


    #[Route('/portfolio', name: 'main_portfolio')]
    public function portfolio(): Response
    {
        return $this->render('main/portfolio.html.twig');
    }
}
