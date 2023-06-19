<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class ArticleController extends AbstractController
{
    #[Route('/article/create', name: 'create_article')]
    public function createArticle(Request $request, EntityManagerInterface $entityManager): Response
    {
        $article = new Article($entityManager);
        $form = $this->createForm(ArticleFormType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $title = $form->get('title')->getData();
            $article->setTitle($title);

            // Enregistrez l'article dans la base de données
            $article->save();

            // Redirigez vers une autre page ou effectuez toute autre action souhaitée
            return $this->redirectToRoute('article_list');
        }

        // Affichez le formulaire avec les erreurs de validation, le cas échéant
        return $this->render('admin/article/create-article.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/modifier-un-article/{id}', name: 'update_article', methods: ['GET', 'POST'])]
    public function updateArticle(Article $article, Request $request, ArticleRepository $repository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ArticleFormType::class, $article)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('image')->getData();

            if ($photo) {
                $this->handleFile($photo, $article, $slugger);
            } else {
                $article->setImage($article->getImage());
            }

            $repository->save($article, true);
            $this->addFlash('success', "La modification a bien été faite");
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('admin/article/create-article.html.twig', [
            'form' => $form->createView(),
            'article' => $article,
            'articles' => $repository->findAll(),
        ]);
    }
    #[Route('/supprimer-un-article/{id}', name: 'hard_delete_article', methods: ['GET'])]
    public function hardDeleteCountry(Article $article, ArticleRepository $repository, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($article);
        $entityManager->flush();
    
        $this->addFlash('success', "L'article a été supprimé définitivement");
        return $this->redirectToRoute('show_dashboard');
    }
    
    private function handleFile(UploadedFile $photo, Article $article, SluggerInterface $slugger)
    {
        $extension = '.' . $photo->guessExtension();
        $safeFilename = $slugger->slug(pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME));
        $newFilename = $safeFilename . '-' . uniqid() . $extension;

        try {
            $photo->move($this->getParameter('uploads_directory'), $newFilename);
            $article->setImage($newFilename);
        } catch (FileException $exception) {
            // Gérer l'exception en conséquence
        }
    }
}
