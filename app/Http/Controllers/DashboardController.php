<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardController extends Controller
{
    private $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        try {
            $currentSection = request()->get('section', 'popular');
            $currentPage = request()->get('page', 1);
            $perPage = 20;
            $userId = auth()->id();

            // Récupérer les films populaires
            $popularMovies = Cache::remember('popular_movies', 3600, function () {
                $allMovies = [];
                for ($page = 1; $page <= 5; $page++) {
                    $response = Http::withOptions(['verify' => false])
                        ->get('https://api.themoviedb.org/3/movie/popular', [
                            'api_key' => env('TMDB_API_KEY'),
                            'language' => 'fr-FR',
                            'page' => $page
                        ]);

                    if ($response->successful()) {
                        $allMovies = array_merge($allMovies, $response->json()['results']);
                    }
                }
                return $allMovies;
            });

            // Récupérer les films tendance
            $trendingMovies = Cache::remember('trending_movies', 3600, function () {
                $allTrending = [];
                for ($page = 1; $page <= 5; $page++) {
                    $response = Http::withOptions(['verify' => false])
                        ->get('https://api.themoviedb.org/3/trending/movie/week', [
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

            // Sélectionner les films en fonction de la section
            $movies = $currentSection === 'popular' ? $popularMovies : $trendingMovies;

            // Créer le paginator
            $paginator = new LengthAwarePaginator(
                array_slice($movies, ($currentPage - 1) * $perPage, $perPage),
                count($movies),
                $perPage,
                $currentPage,
                ['path' => route('dashboard'), 'query' => ['section' => $currentSection]]
            );

            return view('dashboard', [
                'movies' => $paginator,
                'currentSection' => $currentSection,
                'userId' => $userId
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur dans DashboardController@index: ' . $e->getMessage());
            return view('dashboard', [
                'movies' => [],
                'currentSection' => $currentSection,
                'error' => 'Une erreur est survenue lors de la récupération des films.'
            ]);
        }
    }

    // Méthode helper pour la pagination
    private function paginate($items, $perPage = 20, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage),
            $items->count(),
            $perPage,
            $page,
            $options
        );
    }
} 