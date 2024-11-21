<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class TvController extends Controller
{
    public function index()
    {
        try {
            $currentSection = request()->get('section', 'popular');
            $currentPage = request()->get('page', 1);
            $perPage = 20;

            // Récupérer les séries populaires
            $popularTVShows = Cache::remember('popular_tv_shows', 3600, function () {
                $allShows = [];
                for ($page = 1; $page <= 10; $page++) {
                    $response = Http::withOptions(['verify' => false])
                        ->get('https://api.themoviedb.org/3/tv/popular', [
                            'api_key' => env('TMDB_API_KEY'),
                            'language' => 'fr-FR',
                            'page' => $page
                        ]);

                    if ($response->successful()) {
                        $allShows = array_merge($allShows, $response->json()['results']);
                    }
                }
                return $allShows;
            });

            // Récupérer les séries tendance
            $trendingTVShows = Cache::remember('trending_tv_shows', 3600, function () {
                $allTrending = [];
                for ($page = 1; $page <= 10; $page++) {
                    $response = Http::withOptions(['verify' => false])
                        ->get('https://api.themoviedb.org/3/trending/tv/week', [
                            'api_key' => env('TMDB_API_KEY'),
                            'language' => 'fr-FR',
                            'page' => $page
                        ]);

                    if ($response->successful()) {
                        $allTrending = array_merge($allTrending, $response->json()['results']);
                    }
                }
                return $allTrending;
            });

            // Sélectionner les séries en fonction de la section
            $shows = $currentSection === 'popular' ? $popularTVShows : $trendingTVShows;

            // Créer le paginator
            $paginator = new LengthAwarePaginator(
                array_slice($shows, ($currentPage - 1) * $perPage, $perPage),
                count($shows),
                $perPage,
                $currentPage,
                ['path' => route('tv.index'), 'query' => ['section' => $currentSection]]
            );

            return view('tv.index', [
                'shows' => $paginator,
                'currentSection' => $currentSection
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans TvController@index: ' . $e->getMessage());
            return view('tv.index', [
                'shows' => [],
                'currentSection' => $currentSection,
                'error' => 'Une erreur est survenue lors de la récupération des séries.'
            ]);
        }
    }
} 