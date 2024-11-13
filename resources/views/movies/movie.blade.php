@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section Films -->
        <h1 class="text-3xl font-bold text-white mb-8">Films</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 mb-12">
            @foreach($movies as $movie)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                    @if($movie['poster_path'])
                        <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                             alt="{{ $movie['title'] }}"
                             class="w-full h-72 object-cover">
                    @endif
                    
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-white mb-2">{{ $movie['title'] }}</h2>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-purple-500 font-bold">{{ number_format($movie['vote_average'], 1) }}/10</span>
                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('d/m/Y') }}</span>
                        </div>
                        
                        <a href="{{ route('movies.show', $movie['id']) }}" 
                           class="inline-block w-full text-center bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Section Séries -->
        <h1 class="text-3xl font-bold text-white mb-8">Séries TV</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($tvShows as $tvShow)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                    @if($tvShow['poster_path'])
                        <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                             alt="{{ $tvShow['name'] }}"
                             class="w-full h-72 object-cover">
                    @endif
                    
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-white mb-2">{{ $tvShow['name'] }}</h2>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-purple-500 font-bold">{{ number_format($tvShow['vote_average'], 1) }}/10</span>
                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($tvShow['first_air_date'])->format('d/m/Y') }}</span>
                        </div>
                        
                        <a href="{{ route('tv.show', $tvShow['id']) }}" 
                           class="inline-block w-full text-center bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-colors">
                            Voir les détails
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection 