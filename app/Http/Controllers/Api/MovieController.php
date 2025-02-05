<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class MovieController extends Controller
{
    public function index()
    {
        try {
            // Vérifier si les données sont en cache
            if (Cache::has('home_movies')) {
                return response()->json(Cache::get('home_movies'));
            }

            $response = Http::withoutVerifying()
                ->get('https://api.themoviedb.org/3/movie/popular', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'fr-FR',
                    'page' => 1
                ]);

            if ($response->successful()) {
                $movies = $response->json()['results'];
                
                // Mettre en cache pour 6 heures
                Cache::put('home_movies', $movies, now()->addHours(6));
                
                return response()->json($movies);
            }

            return response()->json([]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
} 