<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class VideoGameApiService
{
    private $httpClient;
    private $apiRawgKey;

    public function __construct(HttpClientInterface $httpClient, string $apiRawgKey)
    {
        $this->httpClient = $httpClient;
        $this->apiRawgKey = $apiRawgKey;
    }

    public function searchGames(string $game): array
    {
        
        $url = "$apiUrl?key=$key&search=$game";

        $response = $client->request('GET', $url);
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
