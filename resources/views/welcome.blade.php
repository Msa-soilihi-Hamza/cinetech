<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Cinétech</title>

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-900">
        <!-- Inclure la navigation -->
        @include('layouts.navigation')

        <!-- Contenu principal -->
        <main class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold text-white mb-6">Films Populaires</h1>
            
            <!-- Grille de films -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Cartes de films -->
                @foreach ($films as $film)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                    <img src="{{ $film->image }}" alt="{{ $film->title }}" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl text-white font-bold mb-2">{{ $film->title }}</h2>
                        <p class="text-gray-300 text-sm mb-2">{{ $film->description }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-500 font-bold">{{ $film->rating }}/10</span>
                            <span class="text-gray-400 text-sm">{{ $film->release_date }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </main>
    </body>
</html>
