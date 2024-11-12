<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
        $results = $this->tmdbService->searchMovies($query);

        return response()->json($results);
    }

    public function showTVShow($id)
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

        $tvShow = $response->json();

        // Pour déboguer et voir les données reçues
        // dd($tvShow);

        return view('tv.show', compact('tvShow'));
    }

    public function tvIndex()
    {
        $popularTVShows = $this->tmdbService->getPopularTVShows();
        $trendingTVShows = $this->tmdbService->getTrendingTVShows();

        return view('tv.index', compact('popularTVShows', 'trendingTVShows'));
    }

    public function show($id)
    {
        // Logique pour afficher un film
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get('https://api.themoviedb.org/3/movie/'.$id)
            ->json();

        return view('movies.show', compact('movie'));
    }
}
