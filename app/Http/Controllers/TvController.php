<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class TvController extends Controller
{
    public function index(Request $request)
    {
        $genres = Cache::remember('tv_genres', 3600, function () {
            return Http::withToken(config('services.tmdb.token'))
                ->get('https://api.themoviedb.org/3/genre/tv/list')
                ->json()['genres'];
        });

        $selectedGenre = $request->genre;
        $shows = $this->fetchShows($selectedGenre);

        if ($request->ajax()) {
            return view('tv._filtered-grid', compact('shows'))->render();
        }

        return view('tv.index', compact('genres', 'shows'));
    }

    private function fetchShows($genre = null)
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
                ->get('https://api.themoviedb.org/3/discover/tv', $params);

            if ($response->successful()) {
                $data = $response->json();
                return new LengthAwarePaginator(
                    $data['results'],
                    $data['total_results'],
                    20,
                    request('page', 1),
                    ['path' => route('tv.index'), 'query' => array_filter(['genre' => $genre])]
                );
            }

            return collect();
        } catch (\Exception $e) {
            \Log::error('Erreur lors de la récupération des séries: ' . $e->getMessage());
            return collect();
        }
    }
} 