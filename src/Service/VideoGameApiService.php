<?php

namespace App\Service;

use App\Entity\Game;
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



    public function test()
    {
        return $this->apiRawgKey;
    }
}
