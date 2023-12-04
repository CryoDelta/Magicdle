<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CardRepository $cardRepository, SetRepository $setRepository): Response
    {
        $whiteCards = $cardRepository->findByExactColors("W");
        $blueCards = $cardRepository->findByExactColors("U");
        $blackCards = $cardRepository->findByExactColors("B");
        $redCards = $cardRepository->findByExactColors("R");
        $greenCards = $cardRepository->findByExactColors("G");
        $colorlessCards = $cardRepository->findByExactColors("");
        $cards = [];

        $randNumber = random_int(0,sizeof($whiteCards));
        $cards[] = $whiteCards[$randNumber];
        $randNumber = random_int(0,sizeof($blueCards));
        $cards[] = $blueCards[$randNumber];
        $randNumber = random_int(0,sizeof($blackCards));
        $cards[] = $blackCards[$randNumber];
        $randNumber = random_int(0,sizeof($redCards));
        $cards[] = $redCards[$randNumber];
        $randNumber = random_int(0,sizeof($greenCards));
        $cards[] = $greenCards[$randNumber];
        $randNumber = random_int(0,sizeof($colorlessCards));
        $cards[] = $colorlessCards[$randNumber];

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'cards' => $cards
        ]);
    }
}
