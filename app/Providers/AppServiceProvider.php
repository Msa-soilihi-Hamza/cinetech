<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Définir la longueur par défaut des chaînes de caractères
        Schema::defaultStringLength(191);
        
        Http::macro('tmdb', function () {
            return Http::withOptions([
                'verify' => false
            ])->baseUrl('https://api.themoviedb.org/3');
        });
    }
}
