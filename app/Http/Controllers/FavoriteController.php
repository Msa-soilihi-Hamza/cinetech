<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Log des données reçues
            Log::info('Tentative d\'ajout aux favoris:', [
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            // Validation
            $validated = $request->validate([
                'tmdb_id' => 'required|integer',
                'type' => 'required|in:movie,tv'
            ]);

            Log::info('Données validées:', $validated);

            // Vérifier si le favori existe déjà
            $existingFavorite = Auth::user()->favorites()
                ->where('tmdb_id', $validated['tmdb_id'])
                ->where('type', $validated['type'])
                ->first();

            if ($existingFavorite) {
                Log::info('Favori déjà existant:', [
                    'favorite_id' => $existingFavorite->id
                ]);
                
                return redirect()->back()
                    ->with('error', 'Ce titre est déjà dans vos favoris');
            }

            // Créer le favori
            $favorite = Auth::user()->favorites()->create([
                'tmdb_id' => $validated['tmdb_id'],
                'type' => $validated['type']
            ]);

            Log::info('Favori créé avec succès:', [
                'favorite_id' => $favorite->id
            ]);

            return redirect()->route('favorites.index')
                ->with('success', 'Ajouté aux favoris avec succès');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erreur de validation:', [
                'errors' => $e->errors()
            ]);
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout aux favoris:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de l\'ajout aux favoris: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            $favorites = Auth::user()->favorites()->latest()->get();
            
            Log::info('Récupération des favoris:', [
                'user_id' => Auth::id(),
                'count' => $favorites->count()
            ]);

            return view('favorites.index', [
                'favorites' => $favorites,
                'totalFavorites' => $favorites->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la récupération des favoris:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return view('favorites.index', [
                'favorites' => collect(),
                'error' => 'Une erreur est survenue lors de la récupération des favoris'
            ]);
        }
    }
} 