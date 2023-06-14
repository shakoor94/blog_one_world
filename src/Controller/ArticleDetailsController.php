<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class ArticleDetailsController extends AbstractController
{
    private $authorizationChecker;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    #[Route('/articles/{id}', name: 'article')]
    public function article(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        // Création d'une nouvelle instance de Comment
        $comment = new Comment();
        $comment->setArticle($article); // Associer le commentaire à l'article actuel
        $comment->setCreatedAt(new \DateTime());
        $comment->setUpdatedAt(new \DateTime()); // Définir la date de mise à jour du commentaire

        // Création du formulaire
        $form = $this->createForm(CommentFormType::class, $comment)
            ->handleRequest($request);

        // Vérification si le formulaire de commentaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifie si l'utilisateur est authentifié
            if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $this->addFlash('notice', 'Veuillez vous connecter pour laisser un commentaire.');
                return $this->redirectToRoute('app_login');
            }

            // Récupération de l'utilisateur actuellement connecté
            $user = $this->getUser();
            if ($user !== null) {
                // Utilisation du pseudo de l'utilisateur pour définir l'auteur du commentaire
                $comment->setPseudo($user->getPseudo());
                $comment->setUser($user);
            }

            // Enregistrement du commentaire en base de données
            $entityManager->persist($comment);
            $entityManager->flush();

            // Redirection vers la page des détails de l'article
            return $this->redirectToRoute('article', ['id' => $article->getId()]);
        }
        
        // Récupération ou définition de la variable $country
        $country = $article->getCountry();
        
        // Affichage du formulaire et des commentaires associés à l'article
        return $this->render('article_details/article_details.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
            'country' => $country
        ]);
    }
}
