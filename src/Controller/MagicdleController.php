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
        srand(intval(date('Ymd')));
        $totalNumberOfCards = sizeof($cardRepository->findAll());
        $id = rand(0,$totalNumberOfCards-1);
        $cardOfTheDay = $cardRepository->findCardById($id)[0];

        $cardInput = $request->query->get("cardInput");
        $success = false;

        if (strcasecmp($cardInput,$cardOfTheDay->getName())===0) {
            $success = true;
        }

        return $this->render('magicdle/index.html.twig', [
            'cardOfTheDay' => $cardOfTheDay,
            'success' => $success
        ]);
    }
}
