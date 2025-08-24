<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/main', name: 'app_main')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    #[Route('/aPropos', name: 'app_aPropos')]
    public function aPropos(): Response
    {
        return $this->render('main/aPropos.html.twig');
    }


}
