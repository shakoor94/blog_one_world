<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AproposController extends AbstractController
{
    private $authorizationChecker;
    private $entityManager;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
      
    }
    #[Route('/a_propos', name: 'show_apropos', methods: ['GET', 'POST'])]
    public function showApropos(Request $request): Response
    {
        
    
          
        
        // Créez une instance de votre formulaire de contact
        $form = $this->createForm(ContactFormType::class);

        // Traitez la soumission du formulaire s'il a été envoyé
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifiez si l'utilisateur est connecté lors de la soumission du formulaire
            if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $this->addFlash('warning', 'Veuillez vous connecter pour envoyer un message.');
                return $this->redirectToRoute('show_apropos');
            }
        }

        // Rendez le modèle en passant le formulaire créé
        return $this->render('apropos/showapropos.html.twig', [
            'controller_name' => 'AproposController',
            'form' => $form->createView(),
        ]);
    }
    
}
