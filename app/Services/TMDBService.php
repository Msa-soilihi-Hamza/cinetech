<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TMDBService
{
    private $baseUrl;
    private $apiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://api.themoviedb.org/3';
        $this->apiKey = 'dea1a14482c9d93ec460415f8ad56b1d';
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
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/trending/movie/week", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
            ]);

            if ($response->successful()) {
                return $response->json()['results'];
            }

            return [];

        } catch (\Exception $e) {
            \Log::error('Erreur TMDB: ' . $e->getMessage());
            return [];
        }
    }

    public function searchMovies($query)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/search/movie", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'query' => $query,
                'page' => 1
            ]);

            if ($response->successful()) {
                return $response->json()['results'];
            }

            return [];

        } catch (\Exception $e) {
            \Log::error('Erreur TMDB: ' . $e->getMessage());
            return [];
        }
    }

    public function getPopularTVShows()
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/tv/popular", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
            ]);

            if ($response->successful()) {
                return $response->json()['results'];
            }

            return [];

        } catch (\Exception $e) {
            \Log::error('Erreur TMDB: ' . $e->getMessage());
            return [];
        }
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