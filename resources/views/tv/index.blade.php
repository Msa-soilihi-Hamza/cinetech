@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 p-4">
    <div class="max-w-7xl mx-auto">
        <!-- Séries Populaires -->
        <h2 class="text-3xl font-bold text-white mb-8">Séries Populaires</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($popularTVShows as $show)
                <a href="{{ route('tv.show', ['id' => $show['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($show['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                                 alt="{{ $show['name'] }}"
                                 class="w-full h-100px object-cover">
                        @else
                            <div class="w-full h-100px bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4 bg-gray-800">
                            <h3 class="text-white text-lg font-semibold mb-2 truncate">{{ $show['name'] }}</h3>
                            <div class="flex justify-between items-center">
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
        <h2 class="text-3xl font-bold text-white mb-8 mt-12">Séries Tendance</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse ($trendingTVShows as $show)
                <a href="{{ route('tv.show', ['id' => $show['id']]) }}" class="block">
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                        @if($show['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w342{{ $show['poster_path'] }}"
                                 alt="{{ $show['name'] }}"
                                 class="w-full h-200px object-cover">
                        @else
                            <div class="w-full h-200px bg-gray-700 flex items-center justify-center">
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