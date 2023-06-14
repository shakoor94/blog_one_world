<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private CommentRepository $commentRepository;

    public function __construct(EntityManagerInterface $entityManager, CommentRepository $commentRepository)
    {
        $this->entityManager = $entityManager;
        $this->commentRepository = $commentRepository;
    }
  
    #[Route('/modifier-une-destination/{id}', name: 'update_country', methods: ['GET', 'POST'])]
    public function updateCountry(Comment $comment, Request $request, CommentRepository $repository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CountryFormType::class, $comment, [
            'comment' => $comment,
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUpdatedAt(new DateTime());

        
          

            $repository->save($comment, true);
            $this->addFlash('success', "La modification a bien été faite");
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('admin/show_dashboard.html.twig', [
            'form' => $form->createView(),
            'comment' => $comment,
            'comments' => $repository->findAll(),
        ]);
    }
    #[Route('/comment/dashboard', name: 'comment_dashboard')]
    public function dashboard(CommentRepository $commentRepository): Response
    {
        $comments = $commentRepository->findAll();
    
        return $this->render('admin/show_dashboard.html.twig', [
            'comments' => $comments,
        ]);
    }

    #[Route('/comment/delete/{id}', name: 'comment_delete')]
    public function delete(Comment $comment): Response
    {
        $this->entityManager->remove($comment);
        $this->entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');

        return $this->redirectToRoute('comment_dashboard');
    }
}
