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
            $params = [
                'api_key' => env('TMDB_API_KEY'),
                'language' => 'fr-FR',
                'sort_by' => 'popularity.desc',
                'page' => request('page', 1)
            ];

            if ($genre) {
                $params['with_genres'] = $genre;
            }

            $response = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/discover/movie', $params);

            if ($response->successful()) {
                $data = $response->json();
                return new LengthAwarePaginator(
                    $data['results'],
                    $data['total_results'],
                    20,
                    request('page', 1),
                    ['path' => route('dashboard'), 'query' => array_filter(['genre' => $genre])]
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
        $selectedGenre = $request->genre;
        $movies = $this->fetchMovies($selectedGenre);

        if ($request->ajax()) {
            return view('movies._filtered-grid', compact('movies'))->render();
        }

        return view('dashboard', compact('genres', 'movies'));
    }
} 