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
        // Initialiser les variables avec des collections vides
        $movies = collect();
        $tvShows = collect();

        try {
            // Récupérer les films depuis le cache ou l'API
            $movies = Cache::remember('home_movies', now()->addHours(6), function () {
                $response = Http::withoutVerifying()
                    ->get('https://api.themoviedb.org/3/movie/popular', [
                        'api_key' => env('TMDB_API_KEY'),
                        'language' => 'fr-FR',
                        'page' => 1
                    ]);

                if ($response->successful()) {
                    return collect($response->json()['results']);
                }
                return collect();
            });

            // Récupérer les séries depuis le cache ou l'API
            $tvShows = Cache::remember('home_tvshows', now()->addHours(6), function () {
                $response = Http::withoutVerifying()
                    ->get('https://api.themoviedb.org/3/tv/popular', [
                        'api_key' => env('TMDB_API_KEY'),
                        'language' => 'fr-FR',
                        'page' => 1
                    ]);

                if ($response->successful()) {
                    return collect($response->json()['results']);
                }
                return collect();
            });
        } catch (\Exception $e) {
            // Logger l'erreur mais continuer avec des collections vides
            \Log::error('Erreur lors du chargement de la page d\'accueil: ' . $e->getMessage());
        }

        return view('home', compact('movies', 'tvShows'));
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
        try {
            // Récupération des détails du film
            $movieDetails = $this->tmdbService->getMovie($id);
            
            // Convertir le tableau en objet PHP
            $movie = (object) $movieDetails;

            // Récupération des vidéos (bandes-annonces)
            $videos = $this->tmdbService->getMovieVideos($id);
            
            // Filtrer pour obtenir uniquement les bandes-annonces YouTube en français ou en anglais
            $trailers = collect($videos)->filter(function($video) {
                return $video['site'] === 'YouTube' 
                    && ($video['type'] === 'Trailer' || $video['type'] === 'Teaser')
                    && in_array($video['iso_639_1'], ['fr', 'en']);
            })->values();

            // Récupérer les commentaires pour ce film
            $comments = Comment::where('commentable_type', 'App\Models\Movie')
                ->where('commentable_id', $id)
                ->whereNull('parent_id')
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('movies.show', [
                'movie' => $movie,
                'trailers' => $trailers,
                'comments' => $comments,
                'mediaType' => 'movie',
                'mediaId' => $id
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans HomeController@showMovie: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Impossible de charger les détails du film.');
        }
    }

    public function showTVShow($id)
    {
        try {
            // Récupération des détails de la série
            $tvShowDetails = $this->tmdbService->getTVShow($id);
            
            // Convertir le tableau en objet PHP
            $tvShow = (object) $tvShowDetails;

            // Récupération des vidéos
            $videos = $this->tmdbService->getTVShowVideos($id);
            
            // Filtrer pour obtenir uniquement les bandes-annonces YouTube en français ou en anglais
            $trailers = collect($videos)->filter(function($video) {
                return $video['site'] === 'YouTube' 
                    && ($video['type'] === 'Trailer' || $video['type'] === 'Teaser')
                    && in_array($video['iso_639_1'], ['fr', 'en']);
            })->values();

            // Récupération des crédits (cast et crew)
            $credits = $this->tmdbService->getTVShowCredits($id);

            // Récupérer les commentaires pour cette série
            $comments = Comment::where('commentable_type', 'App\Models\TvShow')
                ->where('commentable_id', $id)
                ->whereNull('parent_id')
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('tv.show', [
                'tvShow' => $tvShow,
                'trailers' => $trailers,
                'credits' => $credits,
                'comments' => $comments,
                'mediaType' => 'tv',
                'mediaId' => $id
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans HomeController@showTVShow: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Impossible de charger les détails de la série.');
        }
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

    public function getMovies()
    {
        try {
            $response = Http::withoutVerifying()
                ->get('https://api.themoviedb.org/3/movie/popular', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'fr-FR',
                    'page' => 1
                ]);

            if ($response->successful()) {
                $movies = $response->json()['results'];
                Cache::put('home_movies', $movies, now()->addHours(6));
                return response()->json($movies);
            }

            return response()->json([]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des films: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }

    public function getTvShows()
    {
        try {
            $response = Http::withoutVerifying()
                ->get('https://api.themoviedb.org/3/tv/popular', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'fr-FR',
                    'page' => 1
                ]);

            if ($response->successful()) {
                $tvShows = $response->json()['results'];
                Cache::put('home_tvshows', $tvShows, now()->addHours(6));
                return response()->json($tvShows);
            }

            return response()->json([]);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des séries: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue'], 500);
        }
    }

    public function storeComment(Request $request)
    {
        try {
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'commentable_type' => 'required|string',
                'commentable_id' => 'required|integer',
                'parent_id' => 'nullable|integer|exists:comments,id'
            ]);

            // Vérifier si l'utilisateur est authentifié
            if (!auth()->check()) {
                return redirect()->back()->with('error', 'Vous devez être connecté pour laisser un commentaire.');
            }

            // Créer le commentaire
            $comment = Comment::create([
                'user_id' => auth()->id(),
                'content' => $validated['content'],
                'commentable_type' => $validated['commentable_type'],
                'commentable_id' => $validated['commentable_id'],
                'parent_id' => $validated['parent_id'] ?? null
            ]);

            // Récupérer les détails du film ou de la série
            $mediaType = $validated['commentable_type'];
            $mediaId = $validated['commentable_id'];

            // Rediriger vers la bonne page avec un message de succès
            if ($mediaType === 'App\Models\Movie') {
                return redirect()->route('movies.show', $mediaId)
                    ->with('success', 'Votre commentaire a été ajouté avec succès.');
            } else {
                return redirect()->route('tv.show', $mediaId)
                    ->with('success', 'Votre commentaire a été ajouté avec succès.');
            }

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création du commentaire: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du commentaire.');
        }
    }
}
