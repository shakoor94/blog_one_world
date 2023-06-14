<?php

namespace App\Controller;

use App\Entity\Country;
use App\Form\CountryFormType;
use App\Repository\CountryRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/admin')]
class CountryController extends AbstractController
{
    private $uploadsDirectory;

    public function __construct(string $uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    #[Route('/ajouter-une-destination', name: 'create_country', methods: ['GET', 'POST'])]
    public function createCountry(Request $request, CountryRepository $repository, SluggerInterface $slugger): Response
    {
        $country = new Country();

        $form = $this->createForm(CountryFormType::class, $country)
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setCreatedAt(new DateTime());
            $country->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('image')->getData();

            if ($photo) {
                $this->handleFile($photo, $country, $slugger);
            }

            // Save the country (implement your own save logic here)

            // Récupérer le titre du pays
            $title = $country->getTitle();

            // Récupérer tous les pays
            $countries = []; // Implement your own logic to get all countries

            return $this->redirectToRoute('show_destination', [
                'id' => $country->getId(),
                'countries' => $countries,
            ]);
        }

        return $this->render('admin/country/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/modifier-une-destination/{id}', name: 'update_country', methods: ['GET', 'POST'])]
    public function updateCountry(Country $country, Request $request, CountryRepository $repository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(CountryFormType::class, $country, [
            'country' => $country,
        ])->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $country->setUpdatedAt(new DateTime());

            /** @var UploadedFile $photo */
            $photo = $form->get('image')->getData();

            if ($photo) {
                $this->handleFile($photo, $country, $slugger);
            } else {
                $country->setImage($country->getImage());
            }

            // Save the country (implement your own save logic here)

            $this->addFlash('success', "La modification a bien été faite");
            return $this->redirectToRoute('show_dashboard');
        }

        return $this->render('admin/country/create.html.twig', [
            'form' => $form->createView(),
            'country' => $country,
            'countries' => [], // Implement your own logic to get all countries
        ]);
    }

    #[Route('/enregistrer-pays', name: 'save_countries')]
    public function saveCountries(): Response
    {
        $countryData = [
            ['title' => 'Maroc'],
            ['title' => 'Emirats Arabes Unis'],
            ['title' => 'Indonésie'],
            ['title' => 'Allemagne'],
            ['title' => 'Espagne'],
            ['title' => 'France'],
            ['title' => 'Italie'],
            ['title' => 'Malte'],
            ['title' => 'Portugal'],

            // Ajoutez d'autres pays ici
        ];

        foreach ($countryData as $data) {
            $title = $data['title'];

            // Save each country (implement your own save logic here)
        }

        return new Response('Les pays ont été enregistrés dans la base de données.');
    }

    #[Route('/supprimer-une-destination/{id}', name: 'hard_delete_country', methods: ['GET'])]
    public function hardDeleteCountry(Country $country, CountryRepository $repository): Response
    {
        // Remove the country (implement your own logic here)

        $this->addFlash('success', "La destination a été supprimée définitivement");
        return $this->redirectToRoute('show_dashboard');
    }

    private function handleFile(?UploadedFile $file, Country $country, SluggerInterface $slugger)
    {
        if ($file) {
            $destination = $this->uploadsDirectory;

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

            try {
                $file->move($destination, $newFilename);
            } catch (FileException $e) {
                // Gérer l'exception en conséquence
            }

            $country->setImage($newFilename);
        }
    }
}
