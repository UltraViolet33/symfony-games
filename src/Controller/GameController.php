<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\SearchFormType;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[IsGranted('ROLE_USER')]
class GameController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);
     
        $searchForm->handleRequest($request);

        $gameResults = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $gameSearch = $data["search"];
            $gameResults = $this->searchGames($gameSearch, $client);
        }

        return $this->render('games/index.html.twig', ["form" => $searchForm, "games" => $gameResults]);
    }


    private function searchGames(string $game,  HttpClientInterface $client): array
    {
        $key = $this->getParameter('app.apikey');
        $apiUrl = $this->getParameter('app.apiUrl');

        $url = "$apiUrl?key=$key&search=$game";

        $response = $client->request('GET', $url);
        $parsedResponse = $response->toArray();

        $results = [];

        foreach ($parsedResponse['results'] as $game) {
            $results[] = Game::create($game);
        }

        return $results;
    }
}
