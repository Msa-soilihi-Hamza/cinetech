<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Comment;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        $popular = $this->tmdbService->getPopularMovies();
        $trending = $this->tmdbService->getTrendingMovies();

        return view('home', compact('popular', 'trending'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->back();
        }

        $results = $this->tmdbService->searchMovies($query);

        return view('search.results', [
            'results' => $results,
            'query' => $query
        ]);
    }

    public function showMovie($id)
    {
        $response = Http::withOptions([
            'verify' => false
        ])
        ->get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => env('TMDB_API_KEY'),
            'append_to_response' => 'credits',
            'language' => 'fr-FR'
        ]);

        if (!$response->successful()) {
            abort(404);
        }

        $movie = $response->json();
        
        $comments = Comment::where('media_type', 'movie')
                          ->where('media_id', $id)
                          ->with(['user', 'replies.user'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('movies.show', compact('movie', 'comments'));
    }

    public function showTVShow($id)
    {
        $response = Http::withOptions([
            'verify' => false
        ])
        ->get("https://api.themoviedb.org/3/tv/{$id}", [
            'api_key' => env('TMDB_API_KEY'),
            'append_to_response' => 'credits',
            'language' => 'fr-FR'
        ]);

        if (!$response->successful()) {
            abort(404);
        }

        $tvShow = $response->json();
        
        $comments = Comment::where('media_type', 'tv')
                          ->where('media_id', $id)
                          ->with(['user', 'replies.user'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('tv.show', compact('tvShow', 'comments'));
    }

    public function tvIndex()
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
            \Log::error('Erreur dans HomeController@tvIndex: ' . $e->getMessage());
            return view('tv.index', [
                'shows' => [],
                'currentSection' => $currentSection,
                'error' => 'Une erreur est survenue lors de la récupération des séries.'
            ]);
        }
    }
}
