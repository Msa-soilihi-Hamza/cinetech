<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;

class DashboardController extends Controller
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
        $popularTVShows = $this->tmdbService->getPopularTVShows();
        $trendingTVShows = $this->tmdbService->getTrendingTVShows();

        return view('dashboard', compact('popular', 'trending', 'popularTVShows', 'trendingTVShows'));
    }
} 