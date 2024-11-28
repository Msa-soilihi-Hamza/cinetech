<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    private function getGenres()
    {
        return Cache::remember('movie_genres', 3600, function () {
            try {
                $response = Http::withOptions(['verify' => false])
                    ->get('https://api.themoviedb.org/3/genre/movie/list', [
                        'api_key' => env('TMDB_API_KEY'),
                        'language' => 'fr-FR'
                    ]);

                if ($response->successful()) {
                    return $response->json()['genres'];
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la récupération des genres: ' . $e->getMessage());
            }
            return [];
        });
    }

    public function index()
    {
        try {
            $currentPage = request()->get('page', 1);
            $genre = request()->get('genre');
            $perPage = 20;

            // Toujours récupérer les genres en premier
            $genres = $this->getGenres();

            // Si pas de genres, retourner une vue avec un message d'erreur
            if (empty($genres)) {
                return view('dashboard', [
                    'movies' => [],
                    'genres' => [],
                    'error' => 'Impossible de charger les genres.'
                ]);
            }

            $params = [
                'api_key' => env('TMDB_API_KEY'),
                'language' => 'fr-FR',
                'page' => $currentPage,
                'sort_by' => 'popularity.desc'
            ];

            if ($genre) {
                $params['with_genres'] = $genre;
            }

            $response = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/discover/movie', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                $paginator = new LengthAwarePaginator(
                    $data['results'],
                    $data['total_results'],
                    20,
                    $currentPage,
                    ['path' => route('dashboard'), 'query' => array_filter([
                        'genre' => $genre
                    ])]
                );

                return view('dashboard', [
                    'movies' => $paginator,
                    'genres' => $genres,
                    'currentGenre' => $genre
                ]);
            }

            return view('dashboard', [
                'movies' => [],
                'genres' => $genres,
                'error' => 'Erreur lors de la récupération des films.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans DashboardController@index: ' . $e->getMessage());
            return view('dashboard', [
                'movies' => [],
                'genres' => [],
                'error' => 'Une erreur est survenue lors de la récupération des films.'
            ]);
        }
    }
} 