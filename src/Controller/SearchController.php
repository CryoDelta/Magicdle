<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Form\SearchType;
use App\Repository\CardRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search', name: 'app_search')]
    public function index(Request $request, CardRepository $cardRepository): Response
    {
        $results = array_slice($cardRepository->findAll(), 0, 8);

        $searchForm = $this->createForm(SearchType::class);
        $searchForm->handleRequest($request);
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $results = $cardRepository->findByName($searchForm->getData()['search']);
        }

        $filterForm = $this->createForm(FilterType::class);
        $filterForm->handleRequest($request);
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $filterForm->getData();
        }

        return $this->render('search/index.html.twig', [
            'searchForm' => $searchForm,
            'filterForm' => $filterForm,
            'results' => $results
        ]);
    }
}
