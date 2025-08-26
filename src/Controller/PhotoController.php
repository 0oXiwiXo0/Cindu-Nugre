<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PhotoController extends AbstractController
{
    #[Route('/portfolio', name: 'photo_portfolio')]
    public function portfolio(): Response
    {
        return $this->render('photo/portfolio.html.twig');
    }
}
