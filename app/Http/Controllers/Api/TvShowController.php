<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TvShowController extends Controller
{
    public function index()
    {
        try {
            // Vérifier si les données sont en cache
            if (Cache::has('home_tvshows')) {
                return response()->json(Cache::get('home_tvshows'));
            }

            $response = Http::withoutVerifying()
                ->get('https://api.themoviedb.org/3/tv/popular', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'fr-FR',
                    'page' => 1
                ]);

            if ($response->successful()) {
                $tvShows = $response->json()['results'];
                
                // Mettre en cache pour 6 heures
                Cache::put('home_tvshows', $tvShows, now()->addHours(6));
                
                return response()->json($tvShows);
            }

            return response()->json([]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 