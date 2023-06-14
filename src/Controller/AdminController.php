<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Comment;
use App\Entity\Article;
use App\Entity\Country;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/tableau-de-bord', name: 'show_dashboard', methods: ['GET'])]
    public function showDashboard(EntityManagerInterface $entityManager): Response
    {
        try {
            $this->denyAccessUnlessGranted("ROLE_ADMIN");
        } catch (AccessDeniedException $exception) {
            $this->addFlash('danger', "Cette partie du site est réservée");
            return $this->redirectToRoute('app_login');
        }

        $countries = $entityManager->getRepository(Country::class)->findBy(['deletedAt' => null]);
        $articles = $entityManager->getRepository(Article::class)->findBy(['deletedAt' => null]);
        $comments = $entityManager->getRepository(Comment::class)->findAll();

        return $this->render('admin/show_dashboard.html.twig', [
            'countries' => $countries,
            'articles' => $articles,
            'comments' => $comments,
        ]);
    }
}
