<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function contact(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            // ⚡ Ici tu pourrais envoyer un email avec MailerInterface
            // Pour l'instant on affiche juste un flash message
            $this->addFlash('success', 'Merci ' . $data['name'] . ', votre message a bien été envoyé !');

            return $this->redirectToRoute('contact');
        }

        return $this->render('main/contact.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
