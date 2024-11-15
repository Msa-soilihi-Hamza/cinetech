<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Afficher tous les favoris de l'utilisateur connecté
     */
    public function index()
    {
        $favorites = Auth::user()->favorites;
        return response()->json([
            'status' => 'success',
            'favorites' => $favorites
        ]);
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

        $favorite = Auth::user()->favorites()->create([
            'tmdb_id' => $request->tmdb_id,
            'type' => $request->type
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Ajouté aux favoris avec succès'
        ]);
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

        return response()->json([
            'status' => 'success',
            'message' => 'Retiré des favoris avec succès'
        ]);
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