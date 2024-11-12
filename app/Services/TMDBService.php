<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TMDBService
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = env('TMDB_BASE_URL');
        $this->apiKey = env('TMDB_API_KEY');
    }

    public function getPopularMovies()
    {
        $response = Http::withoutVerifying()
            ->get("{$this->baseUrl}/movie/popular", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'page' => 1
            ]);

        return $response->json()['results'];
    }

    public function getTrendingMovies()
    {
        $response = Http::withoutVerifying()
            ->get("{$this->baseUrl}/trending/movie/week", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
            ]);

        return $response->json()['results'];
    }

    public function searchMovies($query)
    {
        $response = Http::get("{$this->baseUrl}/search/movie", [
            'api_key' => $this->apiKey,
            'language' => 'fr-FR',
            'query' => $query,
            'page' => 1
        ]);

        return $response->json()['results'];
    }

    public function getPopularTVShows()
    {
        $response = Http::withoutVerifying()
            ->get("{$this->baseUrl}/tv/popular", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'page' => 1
            ]);

        return $response->json()['results'];
    }

    public function getTrendingTVShows()
    {
        $response = Http::withoutVerifying()
            ->get("{$this->baseUrl}/trending/tv/week", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
            ]);

        return $response->json()['results'];
    }

    public function getTVShowDetails($tvId)
    {
        $response = Http::withoutVerifying()
            ->get("{$this->baseUrl}/tv/{$tvId}", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'append_to_response' => 'credits,videos'
            ]);

        return $response->json();
    }
} 