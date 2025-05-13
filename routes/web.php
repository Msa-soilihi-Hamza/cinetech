<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\TvController;
use App\Http\Controllers\MoviesController;
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

// Routes pour les films
Route::get('/movie/{id}', [HomeController::class, 'showMovie'])->name('movies.show');

// Routes pour les séries
Route::get('/tv/{id}', [HomeController::class, 'showTVShow'])->name('tv.show');

Route::get('/tv', [TvController::class, 'index'])->name('tv.index');
Route::get('/films-et-series', [MovieController::class, 'allMedia'])->name('all.media');
Route::get('/search/autocomplete', [SearchController::class, 'autocomplete'])->name('search.autocomplete');
Route::get('/film', [MoviesController::class, 'index'])->name('film');

// Routes protégées
Route::middleware(['auth'])->group(function () {
    // Routes pour les favoris
    Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
    Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::delete('/favorites/destroy', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
    Route::get('/favorites/check', [FavoriteController::class, 'check'])->name('favorites.check');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes d'administration
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.users.index');
    })->name('dashboard');
    
    // Gestion des utilisateurs
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);

    // Gestion des commentaires
    Route::resource('comments', App\Http\Controllers\Admin\CommentController::class);
});

require __DIR__.'/auth.php';
