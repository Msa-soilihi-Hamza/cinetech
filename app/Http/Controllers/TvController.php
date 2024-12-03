<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class TvController extends Controller
{
    private $genres = [
        'action' => 10759,         // Action & Adventure uniquement
        'animation' => 16,         // Animation
        'comedie' => 35,          // Comédie
        'crime' => '80,9648',     // Crime & Mystère combinés
        'documentaire' => 99,     // Documentaire
        'drame' => 18,            // Drame
        'famille' => 10751,       // Famille
        'kids' => 10762,          // Kids
        'mystere' => 9648,        // Mystère
        'news' => 10763,          // News
        'reality' => 10764,       // Reality
        'sf_fantastique' => 10765, // Science-Fiction & Fantastique
        'soap' => 10766,          // Soap
        'talk' => 10767,          // Talk
        'guerre_politique' => 10768, // Guerre & Politique
        'western' => 37           // Western
    ];

    public function index(Request $request)
    {
        $selectedGenre = $request->genre;
        $shows = $this->fetchShows($selectedGenre);

        if ($request->ajax()) {
            return view('tv._filtered-grid', compact('shows'))->render();
        }

        return view('tv.index', [
            'shows' => $shows,
            'genres' => $this->genres
        ]);
    }

    private function fetchShows($genre = null)
    {
        $page = request('page', 1);
        $cacheKey = 'tv_genre_' . ($genre ?? 'all') . '_page_' . $page;

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
                    'include_null_first_air_dates' => false,
                    'vote_count.gte' => 10
                ];

                if ($genre === '') {
                    $endpoint = 'tv/popular';
                } elseif ($genre && isset($this->genres[$genre])) {
                    $endpoint = 'discover/tv';
                    
                    // Traitement spécial pour le genre action
                    if ($genre === 'action') {
                        $params['with_genres'] = 10759; // Action & Adventure
                        $params['sort_by'] = 'popularity.desc';
                        $params['vote_count.gte'] = 50;
                        $params['vote_average.gte'] = 7;
                    } else {
                        $params['with_genres'] = $this->genres[$genre];
                    }
                    
                    // Ajuster les paramètres selon le genre
                    if ($genre === 'documentaire') {
                        $params['sort_by'] = 'vote_average.desc';
                        $params['vote_count.gte'] = 5;
                    } elseif ($genre === 'animation') {
                        $params['with_keywords'] = '210024|287501';
                    }
                } else {
                    $endpoint = 'tv/popular';
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
                    
                    // Filtrer les résultats pour ne garder que les séries valides
                    $results = collect($data['results'])->filter(function ($item) {
                        return isset($item['first_air_date']) && 
                               isset($item['name']) && 
                               isset($item['vote_average']) &&
                               !empty($item['poster_path']);
                    });

                    // Log du nombre de résultats
                    \Log::info('Résultats trouvés:', [
                        'genre' => $genre,
                        'total' => count($results),
                        'total_original' => $data['total_results']
                    ]);

                    if ($results->isEmpty() && $genre === '') {
                        return $this->fetchPopularShows($page);
                    }

                    return new LengthAwarePaginator(
                        $results->all(),
                        $data['total_results'],
                        20,
                        $page,
                        [
                            'path' => route('tv.index'),
                            'query' => array_filter(['genre' => $genre])
                        ]
                    );
                }

                throw new \Exception('API request failed: ' . $response->status());
            } catch (\Exception $e) {
                \Log::error('Error fetching TV shows: ' . $e->getMessage());
                return new LengthAwarePaginator([], 0, 20, 1);
            }
        });
    }

    private function fetchPopularShows($page)
    {
        $params = [
            'api_key' => env('TMDB_API_KEY'),
            'page' => $page,
            'language' => 'fr-FR',
            'sort_by' => 'popularity.desc',
            'vote_count.gte' => 10
        ];

        $response = Http::withOptions(['verify' => false])
            ->get('https://api.themoviedb.org/3/discover/tv', $params);

        if ($response->successful()) {
            $data = $response->json();
            $results = collect($data['results'])->filter(function ($item) {
                return isset($item['first_air_date']) && 
                       isset($item['name']) && 
                       isset($item['vote_average']) &&
                       !empty($item['poster_path']);
            });

            return new LengthAwarePaginator(
                $results->all(),
                $data['total_results'],
                20,
                $page,
                [
                    'path' => route('tv.index')
                ]
            );
        }

        return new LengthAwarePaginator([], 0, 20, 1);
    }

    public function show($id)
    {
        try {
            // Récupérer les détails de la série avec les vidéos
            $tvShow = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/tv/'.$id, [
                    'api_key' => env('TMDB_API_KEY'),
                    'append_to_response' => 'credits,videos',
                    'language' => 'fr-FR'
                ])
                ->json();

            // Récupérer aussi les vidéos en anglais pour avoir plus de choix
            $englishVideos = Http::withOptions(['verify' => false])
                ->get('https://api.themoviedb.org/3/tv/'.$id.'/videos', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'en-US'
                ])
                ->json();

            // Fusionner les vidéos françaises et anglaises
            if (isset($englishVideos['results'])) {
                $tvShow['videos']['results'] = array_merge(
                    $tvShow['videos']['results'] ?? [],
                    $englishVideos['results']
                );
            }

            // Récupérer les commentaires
            $comments = Comment::with(['user', 'replies.user'])
                ->where('media_type', 'tv')
                ->where('media_id', $id)
                ->whereNull('parent_id')
                ->latest()
                ->get();

            return view('tv.show', compact('tvShow', 'comments'));
        } catch (\Exception $e) {
            \Log::error('Error fetching TV show: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Série non trouvée');
        }
    }
} 