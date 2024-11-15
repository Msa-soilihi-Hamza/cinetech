<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route publique pour la page d'accueil
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/movie/{id}', [HomeController::class, 'showTVShow'])->name('movies.show');
Route::get('/tv/{id}', [HomeController::class, 'showTVShow'])->name('tv.show');
Route::get('/tv', [HomeController::class, 'tvIndex'])->name('tv.index');
Route::get('/films-et-series', [MovieController::class, 'allMedia'])->name('all.media');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    // Ajout de la route dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/favorites/add', [FavoriteController::class, 'store'])->name('favorites.add');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/remove', [FavoriteController::class, 'destroy'])->name('favorites.remove');
});

require __DIR__.'/auth.php';
