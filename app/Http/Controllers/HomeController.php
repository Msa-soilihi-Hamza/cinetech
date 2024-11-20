<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Comment;

class HomeController extends Controller
{
    private $tmdbService;

    public function __construct(TMDBService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    public function index()
    {
        $popular = $this->tmdbService->getPopularMovies();
        $trending = $this->tmdbService->getTrendingMovies();

        return view('home', compact('popular', 'trending'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        
        if (empty($query)) {
            return redirect()->back();
        }

        $results = $this->tmdbService->searchMovies($query);

        return view('search.results', [
            'results' => $results,
            'query' => $query
        ]);
    }

    public function showMovie($id)
    {
        $response = Http::withOptions([
            'verify' => false
        ])
        ->get("https://api.themoviedb.org/3/movie/{$id}", [
            'api_key' => env('TMDB_API_KEY'),
            'append_to_response' => 'credits',
            'language' => 'fr-FR'
        ]);

        if (!$response->successful()) {
            abort(404);
        }

        $movie = $response->json();
        
        $comments = Comment::where('media_type', 'movie')
                          ->where('media_id', $id)
                          ->with(['user', 'replies.user'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('movies.show', compact('movie', 'comments'));
    }

    public function showTVShow($id)
    {
        $response = Http::withOptions([
            'verify' => false
        ])
        ->get("https://api.themoviedb.org/3/tv/{$id}", [
            'api_key' => env('TMDB_API_KEY'),
            'append_to_response' => 'credits',
            'language' => 'fr-FR'
        ]);

        if (!$response->successful()) {
            abort(404);
        }

        $tvShow = $response->json();
        
        $comments = Comment::where('media_type', 'tv')
                          ->where('media_id', $id)
                          ->with(['user', 'replies.user'])
                          ->orderBy('created_at', 'desc')
                          ->get();

        return view('tv.show', compact('tvShow', 'comments'));
    }

    public function tvIndex()
    {
        $popularTVShows = $this->tmdbService->getPopularTVShows();
        $trendingTVShows = $this->tmdbService->getTrendingTVShows();

        return view('tv.index', compact('popularTVShows', 'trendingTVShows'));
    }
}
