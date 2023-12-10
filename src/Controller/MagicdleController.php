<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MagicdleController extends AbstractController
{
    #[Route('/magicdle', name: 'app_magicdle')]
    public function index(): Response
    {
        return $this->render('magicdle/index.html.twig', [
            'controller_name' => 'MagicdleController',
        ]);
    }
}
