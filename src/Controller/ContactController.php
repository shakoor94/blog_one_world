<?php

namespace App\Controller;

use App\Form\ContactFormType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class ContactController extends AbstractController
{
    private $mailer;
    private $security;

    public function __construct(MailerInterface $mailer, Security $security)
    {
        $this->mailer = $mailer;
        $this->security = $security;
    }

    public function submitContactForm(Request $request): Response
    {
        // Vérifiez si l'utilisateur est connecté
        if (!$this->security->isGranted('IS_AUTHENTICATED_FULLY')) {
            // L'utilisateur n'est pas connecté, afficher le message d'erreur et le formulaire de contact
            $this->addFlash('error', 'Vous devez être connecté pour envoyer un message.');

            $form = $this->createForm(ContactFormType::class);

            return $this->render('apropos/showapropos.html.twig', [
                'form' => $form->createView(),
            ]);
        }

        // L'utilisateur est connecté, traiter le formulaire
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $message = $formData['message'];
            $email = (new Email())
                ->from($formData['email'])
                ->to('shakoor.fida1@gmail.com')
                ->subject('Nouveau message de contact')
                ->text($message);

            $this->mailer->send($email);

            $this->addFlash('success', 'Votre message a été envoyé avec succès.');

            // Redirection vers une autre page après le traitement du formulaire
            return $this->redirectToRoute('nom_de_la_route');
        }

        return $this->render('apropos/showapropos.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
