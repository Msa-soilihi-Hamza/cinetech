@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Films Populaires -->
        <h2 class="text-3xl font-bold text-white mb-8">Films Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($popular as $movie)
                <a href="{{ route('movies.show', ['id' => $movie['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($movie['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full h-100px object-cover">
                        @else
                            <div class="w-full h-100px bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4 bg-gray-800">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-white text-lg font-semibold truncate">{{ $movie['title'] }}</h3>
                                @php
                                    $isFavorite = Auth::user()->favorites()
                                        ->where('tmdb_id', $movie['id'])
                                        ->exists();
                                    
                                    // Débogage temporaire
                                    \Log::info('Vérification favori', [
                                        'movie_id' => $movie['id'],
                                        'is_favorite' => $isFavorite
                                    ]);
                                @endphp

                                @if($isFavorite)
                                    <form action="{{ route('favorites.remove') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                        <button type="submit" class="text-purple-500 hover:text-purple-600 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('favorites.add') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                        <input type="hidden" name="type" value="movie">
                                        <button type="submit" class="text-gray-400 hover:text-purple-500 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-500 font-bold">{{ number_format($movie['vote_average'], 1) }}/10</span>
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Aucun film populaire disponible.
                </div>
            @endforelse
        </div>

        <!-- Films Tendance -->
        <h2 class="text-3xl font-bold text-white mb-8 mt-12">Films Tendance</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($trending as $movie)
                <a href="{{ route('movies.show', ['id' => $movie['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($movie['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full h-100px object-cover">
                        @else
                            <div class="w-full h-100px bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4 bg-gray-800">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-white text-lg font-semibold truncate">{{ $movie['title'] }}</h3>
                                @php
                                    $isFavorite = Auth::user()->favorites()
                                        ->where('tmdb_id', $movie['id'])
                                        ->exists();
                                    
                                    // Débogage temporaire
                                    \Log::info('Vérification favori', [
                                        'movie_id' => $movie['id'],
                                        'is_favorite' => $isFavorite
                                    ]);
                                @endphp

                                @if($isFavorite)
                                    <form action="{{ route('favorites.remove') }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                        <button type="submit" class="text-purple-500 hover:text-purple-700 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('favorites.add') }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                        <input type="hidden" name="type" value="movie">
                                        <button type="submit" class="text-gray-400 hover:text-purple-500 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-500 font-bold">{{ number_format($movie['vote_average'], 1) }}/10</span>
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Aucun film tendance disponible.
                </div>
            @endforelse
        </div>

        <!-- Séries Populaires -->
      
@endsection
