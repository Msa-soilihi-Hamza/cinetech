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

        return view('home', compact('movies', 'tvShows'));
    }

    public function show($id)
    {
        try {
            // Récupération des détails du film
            $movieDetails = $this->tmdbService->getMovie($id);

            // Récupération des vidéos (bandes-annonces)
            $videos = $this->tmdbService->getMovieVideos($id);
            
            // Filtrer pour obtenir uniquement les bandes-annonces YouTube en français ou en anglais
            $trailers = collect($videos)->filter(function($video) {
                return $video['site'] === 'YouTube' 
                    && ($video['type'] === 'Trailer' || $video['type'] === 'Teaser')
                    && in_array($video['iso_639_1'], ['fr', 'en']);
            })->values();

            // Debug pour vérifier les données
            \Log::info('Movie Details:', ['movie' => $movieDetails]);
            \Log::info('Trailers:', ['trailers' => $trailers]);

            // Récupération des commentaires
            $comments = Comment::where('media_type', 'movie')
                ->where('media_id', $id)
                ->whereNull('parent_id')
                ->with(['user', 'replies.user'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('movies.show', [
                'movie' => $movieDetails,
                'trailers' => $trailers, // Ajout des bandes-annonces
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
