@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Filtres par genre -->
        <div class="flex justify-center flex-wrap gap-2 mb-8">
            @foreach($genres as $genre)
                <a href="{{ route('tv.index', ['genre' => $genre['id']]) }}" 
                   class="px-3 py-1 rounded-full text-sm {{ request('genre') == $genre['id'] ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white' }}">
                    {{ $genre['name'] }}
                </a>
            @endforeach
            <a href="{{ route('tv.index') }}" 
               class="px-3 py-1 rounded-full text-sm {{ !request('genre') ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white' }}">
                Tout voir
            </a>
        </div>

        <!-- Grille de séries -->
        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
            @forelse ($shows as $show)
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
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-white text-lg font-semibold truncate">{{ $show['name'] }}</h3>
                                <x-favorite-button :id="$show['id']" type="tv" />
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
                                <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Aucune série disponible.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $shows->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
@endsection 