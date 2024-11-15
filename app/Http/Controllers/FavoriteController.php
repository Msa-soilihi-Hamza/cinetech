<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FavoriteController extends Controller
{
    /**
     * Afficher tous les favoris de l'utilisateur connecté
     */
    public function index()
    {
        try {
            // Récupérer les favoris de l'utilisateur
            $userFavorites = Auth::user()->favorites()->get();
            $favorites = collect();

            // Pour chaque favori, récupérer les détails depuis TMDB
            foreach ($userFavorites as $favorite) {
                $type = $favorite->type;
                $url = "https://api.themoviedb.org/3/{$type}/{$favorite->tmdb_id}";
                
                $response = Http::withOptions([
                    'verify' => false
                ])->get($url, [
                    'api_key' => env('TMDB_API_KEY'),
                    'language' => 'fr-FR'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    $favorites->push([
                        'id' => $favorite->id,
                        'tmdb_id' => $favorite->tmdb_id,
                        'type' => $type === 'movie' ? 'Film' : 'Série',
                        'title' => $data['title'] ?? $data['name'] ?? 'Sans titre',
                        'poster_path' => $data['poster_path'] ?? null,
                        'vote_average' => number_format($data['vote_average'] ?? 0, 1)
                    ]);
                }
            }

            // Retourner la vue avec les données
            return view('favorites.index', ['favorites' => $favorites]);

        } catch (\Exception $e) {
            return view('favorites.index', [
                'favorites' => collect(),
                'error' => 'Une erreur est survenue lors de la récupération des favoris.'
            ]);
        }
    }

    /**
     * Ajouter un film/série aux favoris
     */
    public function store(Request $request)
    {
        $request->validate([
            'tmdb_id' => 'required|integer',
            'type' => 'required|in:movie,tv'
        ]);

        // Vérifier si le favori existe déjà
        $existingFavorite = Auth::user()->favorites()
            ->where('tmdb_id', $request->tmdb_id)
            ->where('type', $request->type)
            ->first();

        if ($existingFavorite) {
            if ($request->wantsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ce titre est déjà dans vos favoris'
                ], 422);
            }
            return redirect()->back()->with('error', 'Ce titre est déjà dans vos favoris');
        }

        // Si le favori n'existe pas, on le crée
        Auth::user()->favorites()->create([
            'tmdb_id' => $request->tmdb_id,
            'type' => $request->type
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Ajouté aux favoris avec succès'
            ]);
        }

        return redirect()->back()->with('success', 'Ajouté aux favoris avec succès');
    }

    /**
     * Supprimer un favori
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'tmdb_id' => 'required|integer'
        ]);

        Auth::user()->favorites()
            ->where('tmdb_id', $request->tmdb_id)
            ->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Retiré des favoris avec succès'
            ]);
        }

        return redirect()->back()->with('success', 'Retiré des favoris avec succès');
    }

    /**
     * Vérifier si un film/série est dans les favoris
     */
    public function check(Request $request)
    {
        $request->validate([
            'tmdb_id' => 'required|integer'
        ]);

        $isFavorite = Auth::user()->favorites()
            ->where('tmdb_id', $request->tmdb_id)
            ->exists();

        return response()->json([
            'status' => 'success',
            'is_favorite' => $isFavorite
        ]);
    }
} 