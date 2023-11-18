<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\SearchFormType;
use App\Form\SearchType;
use App\Service\VideoGameApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[IsGranted('ROLE_USER')]
class GameController extends AbstractController
{
    public function __construct(private VideoGameApiService $videoGameApiService)
    {
    }

    #[Route('/', name: 'home')]
    public function index(Request $request, HttpClientInterface $client): Response
    {
        $searchForm = $this->createForm(SearchFormType::class);

        $searchForm->handleRequest($request);

        $gameResults = [];

        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $data = $searchForm->getData();
            $gameSearch = $data["search"];
            $gameResults = $this->videoGameApiService->searchGames($gameSearch);
        }

        return $this->render('games/index.html.twig', ["form" => $searchForm, "games" => $gameResults]);
    }

    #[Route('/add-game/{id}', name: 'add_game')]
    public function addGame($id, HttpClientInterface $client, EntityManagerInterface  $entityManager): Response
    {
        // check if game already exists in db
        $game = $entityManager->getRepository(Game::class)->findOneBy(['idRawgAPI' => $id]);

        // game does not already exist in db
        // get the details game from api
        if (!$game) {
            $game = $this->videoGameApiService->searchGameById($id, $client);
            $entityManager->persist($game);
        }

        $user = $this->getUser();

        $user->addGame($game);
        $entityManager->flush();

        return $this->redirectToRoute('user_games');
    }
}
