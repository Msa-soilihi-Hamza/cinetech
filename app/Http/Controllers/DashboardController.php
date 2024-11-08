<?php

namespace App\Http\Controllers;

use App\Services\TMDBService;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $tmdb = new TMDBService();
        
        $popular = collect($tmdb->getPopularMovies())->take(4);
        $trending = collect($tmdb->getTrendingMovies())->take(4);

        return view('dashboard', [
            'popular' => $popular,
            'trending' => $trending
        ]);
    }
} 