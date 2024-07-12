<?php

namespace App\Service;

use App\Entity\Game;
use DateTime;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class VideoGameApiService
{
    private $httpClient;
    private $apiRawgKey;
    private $apiRawgBaseUrl;

    public function __construct(HttpClientInterface $httpClient, string $apiRawgKey, string $apiRawgBaseUrl)
    {
        $this->httpClient = $httpClient;
        $this->apiRawgKey = $apiRawgKey;
        $this->apiRawgBaseUrl = $apiRawgBaseUrl;
    }

    public function searchGames(string $game): array
    {
        
        $url = "$this->apiRawgBaseUrl?key=$this->apiRawgKey&search=$game";

        $response = $this->httpClient->request('GET', $url);
        $parsedResponse = $response->toArray();

        $results = [];

        foreach ($parsedResponse['results'] as $game) {
            $results[] = Game::create($game);
        }

        return $results;
    }

     // get a single games from the api by its id
     public function searchGameById(int $id): Game
     {
         $url = "$this->apiRawgBaseUrl/$id?key=$this->apiRawgKey";
 
         $response = $this->httpClient->request('GET', $url);

         $gameDetails = $response->toArray();
         $newGameObject = new Game();
         $newGameObject->setIdRawgAPI($gameDetails["id"]);
         $newGameObject->setName($gameDetails["name"]);
         $newGameObject->setImagePath($gameDetails["background_image"]);
         $newGameObject->setReleased(new DateTime($gameDetails["released"]));
         $newGameObject->setDescription($gameDetails["description"]);
 
         return $newGameObject;
     }
}
