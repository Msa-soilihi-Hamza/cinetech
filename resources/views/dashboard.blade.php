@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Films Populaires -->
        <h2 class="text-2xl font-bold text-white mb-6">Films Populaires</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($popular as $movie)
                <a href="{{ route('movies.show', ['id' => $movie['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($movie['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w342{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full h-[200px] object-cover">
                        @else
                            <div class="w-full h-[200px] bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-3 bg-gray-800">
                            <h3 class="text-white text-sm font-semibold mb-2 truncate">{{ $movie['title'] }}</h3>
                            <div class="flex justify-between items-center text-xs">
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
        <h2 class="text-2xl font-bold text-white mb-6 mt-12">Films Tendance</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($trending as $movie)
                <a href="{{ route('movies.show', ['id' => $movie['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($movie['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w342{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full h-[200px] object-cover">
                        @else
                            <div class="w-full h-[200px] bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-3 bg-gray-800">
                            <h3 class="text-white text-sm font-semibold mb-2 truncate">{{ $movie['title'] }}</h3>
                            <div class="flex justify-between items-center text-xs">
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
        <h2 class="text-2xl font-bold text-white mb-6 mt-12">Séries Populaires</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($popularTVShows as $show)
                <a href="{{ route('tv.show', ['id' => $show['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($show['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w342{{ $show['poster_path'] }}"
                                 alt="{{ $show['name'] }}"
                                 class="w-full h-[200px] object-cover">
                        @else
                            <div class="w-full h-[200px] bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-3 bg-gray-800">
                            <h3 class="text-white text-sm font-semibold mb-2 truncate">{{ $show['name'] }}</h3>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Aucune série populaire disponible.
                </div>
            @endforelse
        </div>

        <!-- Séries Tendance -->
        <h2 class="text-2xl font-bold text-white mb-6 mt-12">Séries Tendance</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @forelse ($trendingTVShows as $show)
                <a href="{{ route('tv.show', ['id' => $show['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($show['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w342{{ $show['poster_path'] }}"
                                 alt="{{ $show['name'] }}"
                                 class="w-full h-[200px] object-cover">
                        @else
                            <div class="w-full h-[200px] bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-3 bg-gray-800">
                            <h3 class="text-white text-sm font-semibold mb-2 truncate">{{ $show['name'] }}</h3>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Aucune série tendance disponible.
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
