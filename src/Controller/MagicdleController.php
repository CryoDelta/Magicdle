<?php

namespace App\Controller;

use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagicdleController extends AbstractController
{
    #[Route('/magicdle', name: 'app_magicdle')]
    public function index(CardRepository $cardRepository, Request $request): Response
    {
        $session = $request->getSession();
        if (!$session->get("lastDay", false)) { // if lastDay isn't initialized, initialize it
            // set last day as current day
            $session->set("lastDay", date("Ymd"));
            $session->set("wonToday",false);

            // get today's card
            srand(intval(date('Ymd')));
            $totalNumberOfCards = sizeof($cardRepository->findAll());
            $id = rand(0,$totalNumberOfCards-1);
            $cardOfTheDay = $cardRepository->findCardById($id)[0];
            $cardOfTheDayId = $cardOfTheDay->getId();
            $cardOfTheDayName = $cardOfTheDay->getName();
            $session->set("cardOfTheDayId", $cardOfTheDayId);
            $session->set("cardOfTheDayName", $cardOfTheDayName);
        }
        else if (date("Ymd")!=$session->get("lastDay") || !$session->get("cardOfTheDay", false)){
            if (date("Ymd")!=$session->get("lastDay")) {
                $session->set("lastDay", date("Ymd"));
                $session->set("wonToday",false);
            }

            srand(intval(date('Ymd')));
            $totalNumberOfCards = sizeof($cardRepository->findAll());
            $id = rand(0,$totalNumberOfCards-1);
            $cardOfTheDay = $cardRepository->findCardById($id)[0];
            $cardOfTheDayId = $cardOfTheDay->getId();
            $cardOfTheDayName = $cardOfTheDay->getName();
            $session->set("cardOfTheDayId", $cardOfTheDayId);
            $session->set("cardOfTheDayName", $cardOfTheDayName);
        }
        else {
            $cardOfTheDayId = $session->get("cardOfTheDayId",false);
            $cardOfTheDayName = $session->get("cardOfTheDayName",false);
        }

        $cardInputName = $request->query->get("cardInput");

        if ($session->get("guesses",false)) {
            $firstGuess = false;
            $guesses = $session->get("guesses",false);
        }
        else {
            $firstGuess = true;
            $guesses = [];
        }

        // skip if today's card was already found or there's no input
        if (!$session->get("wonToday",false)) {
            if ($cardInputName != null){
                $cardInput = $cardRepository->findCardByName($cardInputName);
                if ($cardInput != null){
                    $cardInput = $cardInput[0];

                    $cardOfTheDay = $cardRepository->findCardById($cardOfTheDayId)[0];

                    $cardInputCaracteristics = ["name" => $cardInputName,
                        "color" => [$cardInput->getColors(),$this->compareColors($cardInput->getColors(),$cardOfTheDay->getColors())],
                        "colorIdentity" => [$cardInput->getColorIdentity(),$this->compareColors($cardInput->getColorIdentity(),$cardOfTheDay->getColorIdentity())],
                        "cmc" => [$cardInput->getManaValue(),$cardInput->getManaValue()==$cardOfTheDay->getManaValue()],
                        "typeline" => [$cardInput->getTypeline(), $this->compareTypelines($cardInput->getTypeline(),$cardOfTheDay->getTypeline())],
                        "power" => [$cardInput->getPower(),$cardInput->getPower()==$cardOfTheDay->getPower()],
                        "toughness" => [$cardInput->getToughness(),$cardInput->getToughness()==$cardOfTheDay->getToughness()],
                        "loyalty" => [$cardInput->getLoyalty(),$cardInput->getLoyalty()==$cardOfTheDay->getLoyalty()],
                        "defense" => [$cardInput->getDefense(),$cardInput->getDefense()==$cardOfTheDay->getDefense()],
                        "rarity" => [$cardInput->getRarity(),$cardInput->getRarity()==$cardOfTheDay->getRarity()],
                        "artist" => [$cardInput->getArtist(),$cardInput->getArtist()==$cardOfTheDay->getArtist()]];
                    if (!$firstGuess){
                        if (!$this->alreadyGuessed($cardInputName,$guesses)){
                            $guesses[] = $cardInputCaracteristics;
                            $session->set("guesses",$guesses);
                        }
                    }
                    else {
                        $guesses[] = $cardInputCaracteristics;
                        $session->set("guesses",$guesses);
                    }
                }
            }
        }

        if ($cardInputName!=null) {
            if (strcasecmp($cardInputName,$cardOfTheDayName)===0 || $session->get("wonToday",false)) {
                $cardOfTheDay = $cardRepository->findCardById($cardOfTheDayId)[0];
                $session->set("wonToday",true);
                return $this->render('magicdle/index.html.twig', [
                    'cardOfTheDay' => $cardOfTheDay,
                    'guesses' => $guesses,
                    'success' => true,
                ]);
            }
        }

        return $this->render('magicdle/index.html.twig', [
            'guesses' => $guesses,
            'success' => false
        ]);

    }

    public function alreadyGuessed($cardName, $guesses) : bool {
        $alreadyGuessed = false;
        for ($i = 0; $i < sizeof($guesses); $i++){
            if ($guesses[$i]["name"]==$cardName) {
                $alreadyGuessed = true;
                break;
            }
        }
        return $alreadyGuessed;
    }

    public function compareColors($colorsCard1, $colorsCard2) : int {
        if ($colorsCard1==$colorsCard2){
            return 1;
        }
        if (($colorsCard1=="" && $colorsCard2!="") || ($colorsCard2=="" && $colorsCard1!="")){
            return -1;
        }
        if (strlen($colorsCard1)>strlen($colorsCard2)) {
            $colorsCard2 = str_split($colorsCard2);
            foreach ($colorsCard2 as $color) {
                if (str_contains($colorsCard1,$color)){
                    return 0;
                }
            }
            return -1;
        }
        else {
            $colorsCard1 = str_split($colorsCard1);
            foreach ($colorsCard1 as $color) {
                if (str_contains($colorsCard2,$color)){
                    return 0;
                }
            }
            return -1;
        }
    }

    public function compareTypelines($typeline1, $typeline2) : int {
        if ($typeline1==$typeline2) {
            return 1;
        }
        if (strlen($typeline1) > strlen($typeline2)) {
            $typeline2 = explode(" ", str_replace("-","",$typeline2));
            foreach($typeline2 as $type) {
                if (str_contains($typeline1, $type)) {
                    return 0;
                }
            }
            return -1;
        }
        else {
            $typeline1 = explode(" ", str_replace("-","",$typeline1));
            foreach($typeline1 as $type) {
                if (str_contains($typeline2, $type)) {
                    return 0;
                }
            }
            return -1;
        }
    }
}
