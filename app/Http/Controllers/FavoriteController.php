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
            if (!Auth::check()) {
                Log::warning('Tentative d\'ajout de favori sans authentification');
                return redirect()->back()->with('error', 'Vous devez être connecté');
            }

            Log::info('Données reçues:', [
                'request_all' => $request->all(),
                'user_id' => Auth::id()
            ]);

            $validated = $request->validate([
                'tmdb_id' => 'required|numeric', // il verfie que l'id est un nombre
                'type' => 'required|in:movie,tv' // il verfie que le type est soit movie ou tv
            ]);

            $existingFavorite = Auth::user()->favorites() // il verfie si le favori existe deja pour l'utilisateur
                ->where('tmdb_id', $validated['tmdb_id']) // il verfie si l'id existe deja
                ->where('type', $validated['type']) // il verfie si le type existe deja
                ->first();// il recupere le favori si il existe

            if ($existingFavorite) {
                return redirect()->back() // il redirige vers la page precedente
                    ->with('error', 'Ce titre est déjà dans vos favoris'); // il affiche un message d'erreur
            }

            $favorite = Auth::user()->favorites()->create([ // il cree un favori pour l'utilisateur
                'tmdb_id' => (int)$validated['tmdb_id'], // il verfie que l'id est un nombre
                'type' => $validated['type'] // il verfie que le type est soit movie ou tv
            ]);

            Log::info('Favori créé avec succès:', [
                'favorite_id' => $favorite->id,
                'user_id' => Auth::id(),
                'tmdb_id' => $validated['tmdb_id'],
                'type' => $validated['type']
            ]);

            return redirect()->route('favorites.index') // il redirige vers la page des favoris
                ->with('success', 'Ajouté aux favoris avec succès'); // il affiche un message de succes

        } catch (\Exception $e) { // il capture les erreurs
            Log::error('Erreur détaillée lors de l\'ajout du favori:', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'ajout aux favoris');
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
                'favorites' => collect(), // il recupere les favoris
                'error' => 'Une erreur est survenue: ' . $e->getMessage() // il affiche un message d'erreur
            ]);
        }
    }

    public function destroy(Request $request)
    {
        try {
            Log::info('Tentative de suppression du favori:', [ // il enregistre l'erreur dans les logs
                'user_id' => Auth::id(), // il recupere l'id de l'utilisateur                   
                'tmdb_id' => $request->tmdb_id // il recupere l'id de la série ou du film
            ]);

            $favorite = Auth::user()->favorites() // il recupere les favoris de l'utilisateur
                ->where('tmdb_id', $request->tmdb_id) // il verifie si l'id de la série ou du film existe
                ->first(); // il recupere le favori si il existe

            if (!$favorite) {
                Log::warning('Favori non trouvé');
                return redirect()->back() // il redirige vers la page precedente
                    ->with('error', 'Favori non trouvé'); // il affiche un message d'erreur
            }

            $favorite->delete();

            Log::info('Favori supprimé avec succès');
            return redirect()->back() // il redirige vers la page precedente
                ->with('success', 'Retiré des favoris avec succès'); // il affiche un message de succes

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du favori:', [
                'error' => $e->getMessage() // il recupere le message d'erreur
            ]);
            
            return redirect()->back() // il redirige vers la page precedente
                ->with('error', 'Une erreur est survenue lors de la suppression'); // il affiche un message d'erreur
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

