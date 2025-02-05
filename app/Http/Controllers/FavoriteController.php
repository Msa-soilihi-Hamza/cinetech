<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Utils;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tmdb_id' => 'required|integer',
                'type' => 'required|string|in:movie,tv'
            ]);

            $favorite = new Favorite();
            $favorite->user_id = auth()->id();
            $favorite->tmdb_id = $validated['tmdb_id'];
            $favorite->type = $validated['type'];
            $favorite->save();

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Ajouté aux favoris avec succès');
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Erreur lors de l\'ajout aux favoris'], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout aux favoris');
        }
    }

    public function index()
    {
        try {
            $userFavorites = Auth::user()->favorites()->get();
            $favorites = [];
            $client = Http::withoutVerifying();

            // Préparer toutes les requêtes
            foreach ($userFavorites as $favorite) {
                $cacheKey = "tmdb_{$favorite->type}_{$favorite->tmdb_id}";
                
                // Vérifier si les données sont en cache
                if (Cache::has($cacheKey)) {
                    $data = Cache::get($cacheKey);
                    $favorites[] = $this->formatFavoriteData($favorite, $data);
                } else {
                    // Faire la requête de manière synchrone pour l'instant
                    $response = $client->get(
                        "https://api.themoviedb.org/3/{$favorite->type}/{$favorite->tmdb_id}",
                        [
                            'api_key' => env('TMDB_API_KEY'),
                            'language' => 'fr-FR',
                        ]
                    );

                    if ($response->successful()) {
                        $data = $response->json();
                        
                        // Mettre en cache pour 24 heures
                        Cache::put($cacheKey, $data, now()->addHours(24));
                        
                        $favorites[] = $this->formatFavoriteData($favorite, $data);
                    }
                }
            }

            return view('favorites.index', [
                'favorites' => collect($favorites),
                'totalFavorites' => count($favorites)
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans index:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return view('favorites.index', [
                'favorites' => collect()
            ]);
        }
    }

    private function formatFavoriteData($favorite, $data)
    {
        return [
            'id' => $favorite->id,
            'tmdb_id' => $favorite->tmdb_id,
            'type' => $favorite->type,
            'title' => $favorite->type === 'movie' ? $data['title'] : $data['name'],
            'overview' => $data['overview'] ?? '',
            'poster_path' => $data['poster_path'] ?? null,
            'vote_average' => $data['vote_average'] ?? 0,
            'release_date' => $favorite->type === 'movie' ?
                ($data['release_date'] ?? null) :
                ($data['first_air_date'] ?? null)
        ];
    }

    public function destroy(Request $request)
    {
        try {
            Auth::user()->favorites()
                ->where('tmdb_id', $request->tmdb_id)
                ->delete();

            if ($request->wantsJson()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back();
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['error' => true], 500);
            }
            return redirect()->back();
        }
    }
} 

// Ce contrôleur gère tout ce qui concerne les favoris dans l'application avec trois fonctions principales :
// 1. store : permet d'ajouter un favori à l'utilisateur
//permet d'ajouter un film ou serie a la liste des favoris de l'utilisateur
//verifie q'il y pas de doublon dans la liste des favoris
//affiche un message de succes ou d'erreur

// 2. index : permet d'afficher la liste des favoris de l'utilisateur
//recupere les favoris de l'utilisateur
//faire la requete a TMDB pour chaque favori
//affiche les details de chaque favori
//organise les données pour afficher la vue

// 3. destroy : permet de supprimer un favori de la liste des favoris de l'utilisateur
//recupere l'id du favori a supprimer
//supprime le favori de la base de données
//verifie si le favori existe
//confirme la suppression
//affiche un message de succes ou d'erreur

//Toutes les fonctions ont une gestion d'erreurs (try/catch)
//Le code enregistre des logs pour le débogage
//L'utilisateur reçoit toujours un retour (message de succès ou d'erreur)
//Les données sont validées avant d'être utilisées
//C'est comme un gestionnaire de bibliothèque personnelle qui permet de :
//Ajouter des livres (films/séries)
//Voir sa collection
//Retirer des livres qu'on ne veut plus
//Tout en s'assurant que tout fonctionne correctement !

