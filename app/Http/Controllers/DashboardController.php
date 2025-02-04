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

    private function fetchMovies($genre = null)
    {
        try {
            // Première requête pour la page 1
            $params = [
                'api_key' => env('TMDB_API_KEY'),
                'language' => 'fr-FR',
                'sort_by' => 'popularity.desc',
                'page' => request('page', 1)
            ];

            if ($genre) {
                $params['with_genres'] = $genre;
            }

            $response1 = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/discover/movie', $params);

            // Deuxième requête pour les 10 films supplémentaires
            $params['page'] = request('page', 1) + 1;
            $response2 = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/discover/movie', $params);

            if ($response1->successful() && $response2->successful()) {
                $data1 = $response1->json();
                $data2 = $response2->json();

                // Combiner les résultats des deux pages
                $results = array_merge(
                    $data1['results'],
                    array_slice($data2['results'], 0, 10) // Prendre seulement les 10 premiers de la deuxième page
                );

                return new LengthAwarePaginator(
                    $results,
                    $data1['total_results'],
                    30,
                    request('page', 1),
                    ['path' => route('film'), 'query' => array_filter(['genre' => $genre])]
                );
            }

            return collect();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des films: ' . $e->getMessage());
            return collect();
        }
    }

    public function index(Request $request)
    {
        $genres = $this->getGenres();
        $selectedGenre = $request->query('genre');
        $movies = $this->fetchMovies($selectedGenre);

        if ($request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('_movies-grid', compact('movies'))->render();
        }

        return view('movies.film', compact('movies', 'genres'));
    }
} 