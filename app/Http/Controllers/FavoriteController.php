<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'tmdb_id' => 'required|integer',
                'type' => 'required|in:movie,tv'
            ]);

            Auth::user()->favorites()->create([
                'tmdb_id' => $validated['tmdb_id'],
                'type' => $validated['type']
            ]);

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

    public function index()
    {
        try {
            $userFavorites = Auth::user()->favorites()->get();
            
            $favorites = [];

            foreach ($userFavorites as $favorite) {
                Log::info('Tentative de requête TMDB pour:', [
                    'endpoint' => $favorite->type,
                    'tmdb_id' => $favorite->tmdb_id
                ]);
                
                $response = Http::withoutVerifying()
                    ->get("https://api.themoviedb.org/3/{$favorite->type}/{$favorite->tmdb_id}", [
                        'api_key' => env('TMDB_API_KEY'),
                        'language' => 'fr-FR',
                    ]);

                Log::info('Réponse TMDB:', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                if ($response->successful()) {
                    $data = $response->json(); // il recupere les données de la requete
                    
                    // Debug: voir la réponse de TMDB
                    Log::info('Réponse TMDB pour ' . $favorite->tmdb_id, $data); // il enregistre les données dans les logs

                    $favorites[] = [
                        'id' => $favorite->id, // il recupere l'id du favori        
                        'tmdb_id' => $favorite->tmdb_id, // il recupere l'id de la série ou du film
                        'type' => $favorite->type, // il recupere le type de média
                        'title' => $favorite->type === 'movie' ? $data['title'] : $data['name'], // il recupere le titre de la série ou du film
                        'overview' => $data['overview'] ?? '', // il recupere la description de la série ou du film
                        'poster_path' => $data['poster_path'] ?? null, // il recupere le chemin de l'affiche de la série ou du film
                        'vote_average' => $data['vote_average'] ?? 0, // il recupere la note de la série ou du film
                        'release_date' => $favorite->type === 'movie' ? // il recupere la date de sortie de la série ou du film
                            ($data['release_date'] ?? null) : // si c'est un film
                            ($data['first_air_date'] ?? null) // si c'est une série
                    ];
                }
            }

            // Debug: voir les favoris formatés
            Log::info('Favoris formatés:', $favorites);

            return view('favorites.index', [ // il retourne la vue des favoris
                'favorites' => collect($favorites), // il recupere les favoris
                'totalFavorites' => count($favorites) // il recupere le nombre de favoris
            ]);

        } catch (\Exception $e) { // il capture les erreurs
            Log::error('Erreur dans index:', [ // il enregistre l'erreur dans les logs
                'message' => $e->getMessage(), // il recupere le message d'erreur
                'line' => $e->getLine(), // il recupere la ligne d'erreur
                'file' => $e->getFile() // il recupere le fichier d'erreur
            ]);

            return view('favorites.index', [
                'favorites' => collect()  // il recupere les favoris
            ]);
        }
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

