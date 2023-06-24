<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use App\Form\ContactFormType;

class ContactController extends AbstractController
{
    private $mailer;
    private $security;
    private $authorizationChecker;

    public function __construct(MailerInterface $mailer, Security $security, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->mailer = $mailer;
        $this->security = $security;
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @Route("/contact", name="contact_form")
     */
    public function submitContactForm(Request $request): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $message = $formData['message'];
            $email = (new Email())
            ->from('test@example.com')
            ->to('shakoor.fida1@gmail.com')
            ->subject('Nouveau message de contact')
            ->text($message);

            if (!$this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $this->addFlash('notice', 'Veuillez vous connecter pour envoyer un message.');
                return $this->redirectToRoute('app_login');
            }

            $this->mailer->send($email);

            return $this->redirectToRoute('confirmation_page');
        }

        return $this->render('apropos/showapropos.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
