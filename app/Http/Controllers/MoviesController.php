<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MoviesController extends Controller
{
    public function index(Request $request)
    {
        $movies = $this->tmdb->getMovies($request->genre);
        
        // Convertir en collection si ce n'est pas déjà le cas
        $movies = collect($movies);
        
        if ($request->ajax()) {
            return view('movies._filtered-grid', compact('movies'))->render();
        }

        $genres = $this->tmdb->getMovieGenres();
        return view('dashboard', compact('movies', 'genres'));
    }
} 