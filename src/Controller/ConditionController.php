<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConditionController extends AbstractController
{
   
    
    #[Route('/politique-confidentialite', name: 'app_politique_confidentialite')]
    public function politiqueConfidentialite(): Response
    {
        return $this->render('condition/politique_confidentialite.html.twig', [
            'controller_name' => 'ConditionController',
        ]);
    }
    
 
    
    #[Route('/cookie', name: 'app_cookie')]
    public function cookie(): Response
    {
        return $this->render('condition/cookie.html.twig', [
            'controller_name' => 'ConditionController',
        ]);
    }
    
    #[Route('/conditions-generales', name: 'app_conditions_generales')]
    public function conditionsGenerales(): Response
    {
        return $this->render('condition/conditions_generales.html.twig', [
            'controller_name' => 'ConditionController',
        ]);
    }
    #[Route('/mentions-legales', name: 'app_mentions_legales')]
    public function mentionsLegales(): Response
    {
        return $this->render('condition/mentions_legales.html.twig', [
            'controller_name' => 'ConditionController',
        ]);
    }
}
