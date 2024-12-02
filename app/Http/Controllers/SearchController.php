<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class SearchController extends Controller
{
    private const API_KEY = 'dea1a14482c9d93ec460415f8ad56b1d';
    private const CACHE_DURATION = 3600; // 1 heure

    public function search(Request $request)
    {
        $validated = $request->validate([
            'query' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'not_regex:/^[\s]*$/',
            ]
        ]);

        $query = trim($validated['query']);
        
        if (empty($query)) {
            return redirect()->back()->with('error', 'Veuillez entrer un terme de recherche valide.');
        }

        $cacheKey = 'search_' . Str::slug($query);

        try {
            return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($query) {
                $movieResults = $this->fetchMovies($query);
                $tvResults = $this->fetchTvShows($query);

                $results = $this->combineResults($movieResults, $tvResults);

                return view('search.results', [
                    'results' => $results,
                    'query' => $query
                ]);
            });

        } catch (\Exception $e) {
            return view('search.results', [
                'results' => [],
                'query' => $query,
                'error' => 'Une erreur est survenue lors de la recherche.'
            ]);
        }
    }

    public function autocomplete(Request $request)
    {
        $validated = $request->validate([
            'query' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'not_regex:/^[\s]*$/',
            ]
        ]);

        $query = trim($validated['query']);
        
        if (empty($query)) {
            return response()->json([]);
        }

        $cacheKey = 'autocomplete_' . Str::slug($query);

        try {
            return Cache::remember($cacheKey, 300, function () use ($query) {
                $response = $this->makeApiRequest('search/multi', [
                    'query' => $query,
                    'language' => 'fr-FR',
                    'page' => 1,
                ]);

                if (!$response->successful()) {
                    return response()->json([]);
                }

                $results = $this->formatAutocompleteResults($response->json()['results'] ?? []);
                return response()->json($results);
            });

        } catch (\Exception $e) {
            return response()->json([]);
        }
    }

    private function fetchMovies(string $query)
    {
        $response = $this->makeApiRequest('search/movie', [
            'query' => $query,
            'include_adult' => false,
            'language' => 'fr-FR',
            'page' => 1,
        ]);

        return $response->json()['results'] ?? [];
    }

    private function fetchTvShows(string $query)
    {
        $response = $this->makeApiRequest('search/tv', [
            'query' => $query,
            'include_adult' => false,
            'language' => 'fr-FR',
            'page' => 1,
        ]);

        return $response->json()['results'] ?? [];
    }

    private function makeApiRequest(string $endpoint, array $params)
    {
        return Http::withOptions([
            'verify' => false
        ])->get("https://api.themoviedb.org/3/{$endpoint}", [
            'api_key' => self::API_KEY,
            ...$params
        ]);
    }

    private function combineResults(array $movies, array $tvShows)
    {
        $formattedMovies = collect($movies)->map(function ($item) {
            return $this->formatMovieResult($item);
        });

        $formattedTvShows = collect($tvShows)->map(function ($item) {
            return $this->formatTvShowResult($item);
        });

        return $formattedMovies->concat($formattedTvShows)
            ->sortByDesc('vote_average')
            ->values()
            ->all();
    }

    private function formatMovieResult(array $item): array
    {
        return [
            'id' => $item['id'],
            'title' => $item['title'],
            'poster_path' => $item['poster_path'] ?? null,
            'vote_average' => $item['vote_average'] ?? 0,
            'release_date' => $item['release_date'] ?? null,
            'media_type' => 'movie',
            'overview' => Str::limit($item['overview'] ?? '', 200)
        ];
    }

    private function formatTvShowResult(array $item): array
    {
        return [
            'id' => $item['id'],
            'title' => $item['name'],
            'poster_path' => $item['poster_path'] ?? null,
            'vote_average' => $item['vote_average'] ?? 0,
            'release_date' => $item['first_air_date'] ?? null,
            'media_type' => 'tv',
            'overview' => Str::limit($item['overview'] ?? '', 200)
        ];
    }

    private function formatAutocompleteResults(array $results): array
    {
        return collect($results)
            ->filter(function ($item) {
                return isset($item['media_type']) && 
                       in_array($item['media_type'], ['movie', 'tv']);
            })
            ->take(5)
            ->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'title' => $item['media_type'] === 'movie' ? 
                        ($item['title'] ?? '') : 
                        ($item['name'] ?? ''),
                    'media_type' => $item['media_type'],
                    'year' => $this->extractYear($item),
                    'poster_path' => $item['poster_path'] ? 
                        'https://image.tmdb.org/t/p/w92' . $item['poster_path'] : 
                        null
                ];
            })
            ->all();
    }

    private function extractYear(array $item): ?string
    {
        $date = $item['release_date'] ?? $item['first_air_date'] ?? null;
        return $date ? substr($date, 0, 4) : null;
    }
} 