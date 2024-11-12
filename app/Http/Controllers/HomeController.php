<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;

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
        $tvShow = $this->tmdbService->getTVShowDetails($id);
        return view('tv.show', compact('tvShow'));
    }

    public function tvIndex()
    {
        $popularTVShows = $this->tmdbService->getPopularTVShows();
        $trendingTVShows = $this->tmdbService->getTrendingTVShows();

        return view('tv.index', compact('popularTVShows', 'trendingTVShows'));
    }
}
