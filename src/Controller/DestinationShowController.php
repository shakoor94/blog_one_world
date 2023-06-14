<?php

namespace App\Controller;

use App\Entity\Country;
use App\Repository\CountryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DestinationShowController extends AbstractController
{
    #[Route('/destination', name: 'show_destination', methods: ['GET'])]
    public function showDestination(CountryRepository $countryRepository): Response
    {
        $countries = $countryRepository->findAll();

        return $this->render('destination/show_destination.html.twig', [
            'countries' => $countries,
        ]);
    }

    #[Route('/destination/{id}', name: 'destination')]
    public function destination(Country $country): Response
    {
        return $this->render('destination/destination.html.twig', [
            'country' => $country,
        ]);
    }
}
