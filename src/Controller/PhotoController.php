<?php

namespace App\Controller;

use App\Entity\Photo;
use App\Form\PhotoType;
use App\Helper\FileUploader;
use App\Repository\PhotoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/photo', name: 'photo_')]
final class PhotoController extends AbstractController
{
    #[Route('/index', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('photo/index.html.twig');
    }

    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(PhotoRepository $photoRepository): Response
    {
        $photos = $photoRepository->findPublishedPhotosWithCategories();
        return $this->render('photo/list.html.twig', ['photos' => $photos]);
    }

    #[Route('/{id}', name: 'detail', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function detail(int $id, PhotoRepository $photoRepository): Response
    {
        $photo = $photoRepository->find($id);
        if (!$photo) {
            throw $this->createNotFoundException('Photo non trouvée');
        }
        return $this->render('photo/detail.html.twig', ['photo' => $photo]);
    }

    #[Route('/create', name: 'create', methods: ['GET','POST'])]
    public function create(Request $request,
                           EntityManagerInterface $em,
                           SluggerInterface $slugger,
                           ParameterBagInterface $parameterBag,
                           FileUploader $fileUploader
    ): Response
    {
                // notre entité vide
        $photo = new Photo();
                // notre formulaire, associée à l'entité vide
        $photoForm = $this->createForm(PhotoType::class, $photo);
                // récupère les données du form et les injecte dans notre $photo
        $photoForm->handleRequest($request);
                // si le formulaire est soumis et valide...
        if ($photoForm->isSubmitted() && $photoForm->isValid()){
                // hydrate les propriétés absentes du formulaire
            $file = $photoForm->get('poster_file')->getData();
            if ($file instanceof UploadedFile) {

                $dir = $parameterBag->get('photo')['poster_directory'];
                $name = $fileUploader->upload(
                    $file,
                    $photo->getTitle() ??'photo', // si le titre est null, on met "photo"
                    $dir,
                );
                if ($photo->getPoster() && file_exists($dir . '/' . $photo->getPoster())) {
                    unlink($dir . '/' . $photo->getPoster());
                }
                $photo->setPoster($name);
            }
            $photo->setIsPublished(true);
                // sauvegarde en bdd
            $em->persist($photo);
            $em->flush();
                // affiche un message sur la prochaine page
            $this->addFlash('success', 'Photo ajouté avec succès !');
                // redirige vers la page de détails de l'idée fraîchement créée
            return $this->redirectToRoute('photo_detail', ['id' => $photo->getId()]);
        }
                // affiche le formulaire
        return $this->render('photo/create.html.twig', [
            'photoForm' => $photoForm,
            'form' => $photoForm->createView(),
            'photo' => $photo,
        ]);
    }
    #[Route('/{id}/update', name: 'update', requirements: ['id'=>'\d+'],
        methods: ['GET','POST'])]
    public function update(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        FileUploader $fileUploader,
        ParameterBagInterface $parameterBag,
        PhotoRepository $photoRepository,
    ): Response
    {
        // récupère cette photo en fonction de l'id présent dans l'URL
        $photo = $photoRepository->find($id);

        // s'il n'existe pas en bdd, on déclenche une erreur 404
        if (!$photo) {
            throw $this->createNotFoundException('Cette photo n\'existe pas');
        }
        // notre formulaire, associée à l'entité vide
        $photoForm = $this->createForm(PhotoType::class, $photo);
        // récupère les données du form et les injecte dans notre $photo
        $photoForm->handleRequest($request);

        // si le formulaire est soumis et valide...
        if ($photoForm->isSubmitted() && $photoForm->isValid()) {
            // hydrate les propriétés absentes du formulaire
            $file = $photoForm->get('poster_file')->getData();
            if ($file instanceof UploadedFile) {
                $dir = $parameterBag->get('photo')['poster_directory'];
                $name = $fileUploader->upload(
                    $file,
                    $photo->getTitle(),
                    $dir
                );
                if ($photo->getPoster() && file_exists($dir . '/' . $photo->getPoster())) {
                    unlink($dir . '/' . $photo->getPoster());
                }
                $photo->setPoster($name);
            }

            // sauvegarde en bdd
            $em->flush();
            // affiche un message sur la prochaine page
            $this->addFlash('success', 'Photo modifié avec succès !');
            // redirige vers la page de détail de l'idée fraîchement modifiée
            return $this->redirectToRoute('photo_detail', ['id' => $photo->getId()]);
        }
        // affiche le formulaire
        return $this->render('photo/create.html.twig', [
            'photoForm' => $photoForm
        ]);
    }

    #[Route('/{id}/delete', name: 'delete', requirements: ['id'=>'\d+'], methods: ['GET'])]
    public function delete(int $id,
                           PhotoRepository $photoRepository,
                           Request $request,
                           EntityManagerInterface $em
    ): Response
    {
        $photo = $photoRepository->find($id);

        if (!$photo) {
            throw $this->createNotFoundException('Cette photo n\'existe pas');
        }

        // sécurité CSRF
        if ($this->isCsrfTokenValid('delete'.$id, $request->query->get('token'))) {
            $em->remove($photo);
            $em->flush();
            $this->addFlash('success', 'Cette photo a été supprimée');
        } else {
            $this->addFlash('danger', 'Cette photo ne peut pas être supprimée');
        }
        return $this->redirectToRoute('photo_list');
    }
}
