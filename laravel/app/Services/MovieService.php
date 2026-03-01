<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class MovieService
{
    protected $client;
    protected $apiKey;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.omdb.api_key');
        $this->apiUrl = config('services.omdb.api_url');
    }

    /**
     * Search movies by title
     *
     * @param string $query
     * @param int $page
     * @return array
     */
    public function searchMovies($query, $page = 1)
    {
        try {
            $response = $this->client->get($this->apiUrl, [
                'query' => [
                    'apikey' => $this->apiKey,
                    's' => $query,
                    'page' => $page,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['Response']) && $data['Response'] === 'True') {
                return [
                    'success' => true,
                    'data' => $data['Search'] ?? [],
                    'total_results' => $data['totalResults'] ?? 0,
                ];
            }

            return [
                'success' => false,
                'message' => $data['Error'] ?? 'No movies found',
                'data' => [],
            ];
        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch movies: ' . $e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Get movie details by IMDb ID
     *
     * @param string $imdbId
     * @return array
     */
    public function getMovieDetails($imdbId)
    {
        try {
            $response = $this->client->get($this->apiUrl, [
                'query' => [
                    'apikey' => $this->apiKey,
                    'i' => $imdbId,
                    'plot' => 'full',
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (isset($data['Response']) && $data['Response'] === 'True') {
                return [
                    'success' => true,
                    'data' => $data,
                ];
            }

            return [
                'success' => false,
                'message' => $data['Error'] ?? 'Movie not found',
                'data' => null,
            ];
        } catch (GuzzleException $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch movie details: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }
}
