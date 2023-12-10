<?php

namespace App\Controller;

use App\Repository\CardRepository;
use App\Repository\SetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(CardRepository $cardRepository, SetRepository $setRepository): Response
    {
        $totalNumberOfCards = sizeof($cardRepository->findAll());

        $allSets = $setRepository->findAll();
        $randomSet = $allSets[random_int(0, sizeof($allSets))];

        $releaseDate = $randomSet->getReleaseDate();
        $releaseYear = $releaseDate->format("Y");
        $releaseMonth = $releaseDate->format("F");
        $releaseDay = $releaseDate->format("j");

        $whiteCards = $cardRepository->findBySetAndExactColors($randomSet->getName(),"W");
        $blueCards = $cardRepository->findBySetAndExactColors($randomSet->getName(),"U");
        $blackCards = $cardRepository->findBySetAndExactColors($randomSet->getName(),"B");
        $redCards = $cardRepository->findBySetAndExactColors($randomSet->getName(),"R");
        $greenCards = $cardRepository->findBySetAndExactColors($randomSet->getName(),"G");
        $colorlessCards = $cardRepository->findBySetAndExactColors($randomSet->getName(),"");
        $cards = [];

        if(sizeof($whiteCards)>0){
            $randNumber = random_int(0,sizeof($whiteCards)-1);
            $cards[] = $whiteCards[$randNumber];
        }

        if(sizeof($blueCards)>0){
            $randNumber = random_int(0,sizeof($blueCards)-1);
            $cards[] = $blueCards[$randNumber];
        }

        if(sizeof($blackCards)>0){
            $randNumber = random_int(0,sizeof($blackCards)-1);
            $cards[] = $blackCards[$randNumber];
        }

        if(sizeof($redCards)>0){
            $randNumber = random_int(0,sizeof($redCards)-1);
            $cards[] = $redCards[$randNumber];
        }

        if(sizeof($greenCards)>0){
            $randNumber = random_int(0,sizeof($greenCards)-1);
            $cards[] = $greenCards[$randNumber];
        }

        if(sizeof($colorlessCards)>0){
            $randNumber = random_int(0,sizeof($colorlessCards)-1);
            $cards[] = $colorlessCards[$randNumber];
        }

        return $this->render('stats/index.html.twig', [
            'controller_name' => 'StatsController',
            'totalNumberOfCards' => $totalNumberOfCards,
            'randomSet' => $randomSet,
            'releaseYear' => $releaseYear,
            'releaseMonth' => $releaseMonth,
            'releaseDay' => $releaseDay,
            'cards' => $cards
        ]);
    }
}
