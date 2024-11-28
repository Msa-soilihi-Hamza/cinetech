<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ActorController extends Controller
{
    public function getMovies($id)
    {
        try {
            $response = Http::withoutVerifying()
                ->get('https://api.themoviedb.org/3/person/' . $id . '/movie_credits', [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'fr-FR'
                ]);

            if (!$response->successful()) {
                Log::error('TMDB API error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json([
                    'error' => 'Erreur API TMDB: ' . $response->status()
                ], $response->status());
            }

            $data = $response->json();
            
            if (empty($data)) {
                return response()->json([
                    'error' => 'Aucune donnée reçue de TMDB'
                ], 404);
            }

            return response()->json($data);

        } catch (\Exception $e) {
            Log::error('Exception dans ActorController', [
                'message' => $e->getMessage(),
                'actor_id' => $id
            ]);
            
            return response()->json([
                'error' => 'Erreur serveur: ' . $e->getMessage()
            ], 500);
        }
    }
}
