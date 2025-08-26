<?php

namespace App\Controller;

use App\Repository\PhotoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PhotoController extends AbstractController
{
    #[Route('/portfolio/{page}', name: 'photo_portfolio', requirements: ['page' => '\d+'], defaults: ['page' => 1], methods: ['GET'])]
    public function portfolio(int $page, ParameterBagInterface $parameters, PhotoRepository $photoRepository): Response
    {
        $nbPerPage = $parameters->get('photo')['nb_max'];
        $offset = ($page - 1) * $nbPerPage;
        $photos = $photoRepository->findBy([], null, $nbPerPage, $offset);
        $total = $photoRepository->count([]);
        $totalPages = ceil($total / $nbPerPage);

        return $this->render('photo/portfolio.html.twig', [
                'photos' => $photos,
                'page' => $page,
                'total_pages' => $totalPages,
            ]
        );
    }
}
