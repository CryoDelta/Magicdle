<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CardController extends AbstractController
{
    #[Route('/card/{id}', name: 'app_card')]
    public function index(Card $id, CardRepository $cardRepository): Response
    {
        if(!$id->getMedia()->isEmpty()){
            $url=$id->getMedia()->get(0)->getPath();
            $image = file_get_contents($url);
            if ($image){
                $cardArt = 'data:image/jpg;base64,' . base64_encode($image);
            } else {
                $cardArt = null;
            }
        } else {
            $cardArt = null;
        }

        $linkedCards = $cardRepository->findByName($id->getName());
        $temp = [];
        foreach ($linkedCards as $linkedCard){
            if ($linkedCard->getName() != $id->getName() && !$linkedCard->getMedia()->isEmpty()){
                $temp[] = $linkedCard;
            }
        }
        $linkedCards = $temp;

        return $this->render('card/index.html.twig', [
            'card' => $id,
            'art' => $cardArt,
            'linkedCards' => $linkedCards
        ]);
    }
}
