<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Http\Request;

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

        return view('movies.movie', [
            'movies' => $movies,
            'tvShows' => $tvShows
        ]);
    }
}
