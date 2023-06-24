<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AstuceController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('astuce/astuces&preparatifs.html.twig');
    }
    #[Route('/astuces_preparatifs', name: 'show_astuce', methods: ['GET'])]
    public function showAstuce(): Response
    {
        return $this->render('astuce/astuces&preparatifs.html.twig', [
            'controller_name' => 'AstuceController',
        ]);
    }

}
