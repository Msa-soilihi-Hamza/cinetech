<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;
use App\Models\Comment;

class MovieController extends Controller
{
    private $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function allMedia()
    {
        $movies = $this->tmdbService->getTrendingMovies();
        $tvShows = $this->tmdbService->getTrendingTVShows();

        // Convertir les tableaux en collections
        $movies = collect($movies);
        $tvShows = collect($tvShows);

        return view('movies.movie', compact('movies', 'tvShows'));
    }

    public function show($id)
    {
        try {
            // Récupération des détails du film depuis l'API
            $movieDetails = $this->tmdbService->getMovie($id);

            // Debug pour vérifier les données
            \Log::info('Movie Details:', ['movie' => $movieDetails]);

            // Récupération des commentaires
            $comments = Comment::where('media_type', 'movie')
                ->where('media_id', $id)
                ->whereNull('parent_id')
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            // Debug pour vérifier les commentaires
            \Log::info('Comments:', ['comments' => $comments]);

            return view('movies.show', [
                'movie' => $movieDetails,
                'comments' => $comments,
                'mediaType' => 'movie',
                'mediaId' => $id
            ]);
        } catch (\Exception $e) {
            \Log::error('Erreur dans MovieController@show: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue.');
        }
    }
}
