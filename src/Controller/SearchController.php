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
            // Data collection formatting
            $filters = $filterForm->getData();
            $colorMode = $filters["color"]["mode"];
            if ($filters["color"]["type"] != [] && $colorMode != null){
                $colorTypes = implode('', $filters["color"]["type"]);
            } else {
                $colorTypes = null;
                $colorMode = null;
            }
            $statsType = $filters["stats"]["type"];
            $statsOperator = $filters["stats"]["operator"];
            $statsValue = $filters["stats"]["value"];
            if ($statsType == null || $statsOperator == null || $statsValue == null) {
                $statsType = null;
                $statsOperator = null;
                $statsValue = null;
            }

            // Applying filters
            $results = $cardRepository->findAllFiltered(
                $colorTypes, $colorMode,
                $filters["typeLine"], $filters["set"],
                $filters["effectText"], $filters["flavorText"],
                $statsType, $statsOperator, $statsValue,
                $filters["rarity"], $filters["artist"]
            );
        }

        return $this->render('search/index.html.twig', [
            'searchForm' => $searchForm,
            'filterForm' => $filterForm,
            'results' => $results
        ]);
    }
}
