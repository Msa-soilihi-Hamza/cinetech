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
        $this->apiKey = env('TMDB_API_KEY', 'dea1a14482c9d93ec460415f8ad56b1d');
    }

    // Méthodes pour les films
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

    public function getMovie($id)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/movie/{$id}", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'append_to_response' => 'videos,credits'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Erreur lors de la récupération des détails du film.');
        } catch (\Exception $e) {
            \Log::error('Erreur TMDB: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getMovieVideos($id)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/movie/{$id}/videos", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR'
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

    // Méthodes pour les séries
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

    public function getTVShow($id)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/tv/{$id}", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR',
                'append_to_response' => 'videos,credits'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Erreur lors de la récupération des détails de la série.');
        } catch (\Exception $e) {
            \Log::error('Erreur TMDB: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getTVShowVideos($id)
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/tv/{$id}/videos", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR'
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

    /**
     * Récupère les crédits (cast et crew) d'une série
     *
     * @param int $id ID de la série
     * @return array Les crédits de la série
     */
    public function getTVShowCredits(int $id): array
    {
        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get("{$this->baseUrl}/tv/{$id}/credits", [
                'api_key' => $this->apiKey,
                'language' => 'fr-FR'
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];

        } catch (\Exception $e) {
            \Log::error('Erreur TMDB: ' . $e->getMessage());
            return [];
        }
    }
}