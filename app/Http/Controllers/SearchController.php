<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->back();
        }

        try {
            // Recherche de films
            $movieResults = Http::withOptions([
                'verify' => false
            ])->get('https://api.themoviedb.org/3/search/movie', [
                'api_key' => 'dea1a14482c9d93ec460415f8ad56b1d',
                'query' => $query,
                'include_adult' => false,
                'language' => 'fr-FR',
                'page' => 1,
            ]);

            // Recherche de séries TV
            $tvResults = Http::withOptions([
                'verify' => false
            ])->get('https://api.themoviedb.org/3/search/tv', [
                'api_key' => 'dea1a14482c9d93ec460415f8ad56b1d',
                'query' => $query,
                'include_adult' => false,
                'language' => 'fr-FR',
                'page' => 1,
            ]);

            $movies = collect($movieResults->json()['results'] ?? [])->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'title' => $item['title'],
                    'poster_path' => $item['poster_path'] ?? null,
                    'vote_average' => $item['vote_average'] ?? 0,
                    'release_date' => $item['release_date'] ?? null,
                    'media_type' => 'movie',
                    'overview' => $item['overview'] ?? ''
                ];
            });

            $tvShows = collect($tvResults->json()['results'] ?? [])->map(function ($item) {
                return [
                    'id' => $item['id'],
                    'title' => $item['name'],
                    'poster_path' => $item['poster_path'] ?? null,
                    'vote_average' => $item['vote_average'] ?? 0,
                    'release_date' => $item['first_air_date'] ?? null,
                    'media_type' => 'tv',
                    'overview' => $item['overview'] ?? ''
                ];
            });

            // Combine et mélange les résultats
            $results = $movies->concat($tvShows)->sortByDesc('vote_average')->values()->all();

            return view('search.results', [
                'results' => $results,
                'query' => $query
            ]);

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
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        try {
            $response = Http::withOptions([
                'verify' => false
            ])->get('https://api.themoviedb.org/3/search/multi', [
                'api_key' => 'dea1a14482c9d93ec460415f8ad56b1d',
                'query' => $query,
                'language' => 'fr-FR',
                'page' => 1,
            ]);

            if ($response->successful()) {
                $results = collect($response->json()['results'])
                    ->filter(function ($item) {
                        return isset($item['media_type']) && in_array($item['media_type'], ['movie', 'tv']);
                    })
                    ->take(5)
                    ->map(function ($item) {
                        return [
                            'id' => $item['id'],
                            'title' => $item['media_type'] === 'movie' ? ($item['title'] ?? '') : ($item['name'] ?? ''),
                            'media_type' => $item['media_type'],
                            'year' => isset($item['release_date']) ? substr($item['release_date'], 0, 4) : 
                                   (isset($item['first_air_date']) ? substr($item['first_air_date'], 0, 4) : null),
                            'poster_path' => $item['poster_path'] ? 'https://image.tmdb.org/t/p/w92' . $item['poster_path'] : null
                        ];
                    });

                return response()->json($results);
            }

            return response()->json([]);

        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
} 