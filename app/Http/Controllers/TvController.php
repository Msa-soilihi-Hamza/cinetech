<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class TvController extends Controller
{
    private function getGenres()
    {
        return Cache::remember('tv_genres', 3600, function () {
            try {
                $response = Http::withOptions(['verify' => false])
                    ->get('https://api.themoviedb.org/3/genre/tv/list', [
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

    public function index(Request $request)
    {
        try {
            $currentPage = $request->get('page', 1);
            $genre = $request->get('genre');
            $perPage = 20;

            $genres = $this->getGenres();

            if (empty($genres)) {
                return view('tv.index', [
                    'shows' => [],
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
                ->get('https://api.themoviedb.org/3/discover/tv', $params);

            if ($response->successful()) {
                $data = $response->json();
                
                $paginator = new LengthAwarePaginator(
                    $data['results'],
                    $data['total_results'],
                    20,
                    $currentPage,
                    ['path' => route('tv.index'), 'query' => array_filter([
                        'genre' => $genre
                    ])]
                );

                if ($request->ajax()) {
                    return view('components.shows-grid', [
                        'shows' => $paginator
                    ])->render();
                }

                return view('tv.index', [
                    'shows' => $paginator,
                    'genres' => $genres,
                    'currentGenre' => $genre
                ]);
            }

            return view('tv.index', [
                'shows' => [],
                'genres' => $genres,
                'error' => 'Erreur lors de la récupération des séries.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans TvController@index: ' . $e->getMessage());
            return view('tv.index', [
                'shows' => [],
                'genres' => [],
                'error' => 'Une erreur est survenue lors de la récupération des séries.'
            ]);
        }
    }
} 