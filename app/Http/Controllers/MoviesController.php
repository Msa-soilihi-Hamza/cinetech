<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class MoviesController extends Controller
{
    private $genres = [
        'action' => 28,
        'aventure' => 12,
        'animation' => 16,
        'comedie' => 35,
        'crime' => 80,
        'documentaire' => 99,
        'drame' => 18,
        'famille' => 10751,
        'fantastique' => 14,
        'histoire' => 36,
        'horreur' => 27,
        'musique' => 10402,
        'mystere' => 9648,
        'romance' => 10749,
        'science_fiction' => 878,
        'telefilm' => 10770,
        'thriller' => 53,
        'guerre' => 10752,
        'western' => 37
    ];

    public function index(Request $request)
    {
        // Forcer le rafraîchissement du cache des genres
        Cache::forget('movie_genres');
        
        $selectedGenre = $request->genre;
        $movies = $this->fetchMovies($selectedGenre);

        if ($request->ajax()) {
            return view('movies._filtered-grid', compact('movies'))->render();
        }

        return view('movies.film', [
            'movies' => $movies,
            'genres' => $this->getGenres()
        ]);
    }

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
                    $genres = $response->json()['genres'];
                    // Trier les genres par nom
                    usort($genres, function($a, $b) {
                        return strcmp($a['name'], $b['name']);
                    });
                    return $genres;
                }
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la récupération des genres: ' . $e->getMessage());
            }
            return [];
        });
    }

    private function fetchMovies($genre = null)
    {
        $page = request('page', 1);
        $cacheKey = 'movies_genre_' . ($genre ?? 'all') . '_page_' . $page;

        // Effacer le cache pour ce genre spécifique
        Cache::forget($cacheKey);

        return Cache::remember($cacheKey, 3600, function () use ($genre, $page) {
            try {
                $params = [
                    'api_key' => env('TMDB_API_KEY'),
                    'page' => $page,
                    'language' => 'fr-FR',
                    'sort_by' => 'popularity.desc',
                    'include_adult' => false,
                    'vote_count.gte' => 10
                ];

                if (empty($genre)) {
                    $endpoint = 'movie/popular';
                } else {
                    $endpoint = 'discover/movie';
                    $params['with_genres'] = $genre;
                    
                    // Ajuster les paramètres selon le genre
                    switch($genre) {
                        case '99': // Documentaire
                            $params['sort_by'] = 'vote_average.desc';
                            $params['vote_count.gte'] = 5;
                            break;
                        case '28': // Action
                            $params['vote_count.gte'] = 50;
                            $params['vote_average.gte'] = 7;
                            break;
                        case '878': // Science Fiction
                            $params['vote_count.gte'] = 20;
                            break;
                        case '16': // Animation
                            $params['vote_count.gte'] = 30;
                            break;
                    }
                }

                \Log::info('Requête API TMDB:', [
                    'endpoint' => $endpoint,
                    'genre' => $genre,
                    'params' => array_merge($params, ['api_key' => '***'])
                ]);

                $response = Http::withOptions(['verify' => false])
                    ->get('https://api.themoviedb.org/3/' . $endpoint, $params);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    // Filtrer les résultats pour ne garder que les films valides
                    $results = collect($data['results'])->filter(function ($item) {
                        return isset($item['release_date']) && 
                               isset($item['title']) && 
                               isset($item['vote_average']) &&
                               !empty($item['poster_path']);
                    });

                    return new LengthAwarePaginator(
                        $results->all(),
                        $data['total_results'],
                        20,
                        $page,
                        [
                            'path' => route('film'),
                            'query' => array_filter(['genre' => $genre])
                        ]
                    );
                }

                throw new \Exception('API request failed: ' . $response->status());
            } catch (\Exception $e) {
                \Log::error('Error fetching movies: ' . $e->getMessage());
                return new LengthAwarePaginator([], 0, 20, 1);
            }
        });
    }

    public function show($id)
    {
        try {
            // Récupérer les détails du film avec les vidéos
            $movie = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/movie/'.$id, [
                    'api_key' => env('TMDB_API_KEY'),
                    'append_to_response' => 'credits,videos',
                    'language' => 'fr-FR'
                ])
                ->json();

            // Récupérer aussi les vidéos en anglais pour avoir plus de choix
            $englishVideos = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/movie/'.$id.'/videos', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'en-US'
                ])
                ->json();

            // Fusionner les vidéos françaises et anglaises
            if (isset($englishVideos['results'])) {
                $movie['videos']['results'] = array_merge(
                    $movie['videos']['results'] ?? [],
                    $englishVideos['results']
                );
            }

            // Récupérer les commentaires
            $comments = Comment::with(['user', 'replies.user'])
                ->where('media_type', 'movie')
                ->where('media_id', $id)
                ->whereNull('parent_id')
                ->latest()
                ->get();

            return view('movies.show', compact('movie', 'comments'));
        } catch (\Exception $e) {
            \Log::error('Error fetching movie: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Film non trouvé');
        }
    }
} 