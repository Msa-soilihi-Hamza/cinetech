@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Navigation des sections -->
        <div class="flex justify-center space-x-4 mb-8">
            <a href="{{ route('dashboard', ['section' => 'popular']) }}" 
               class="px-4 py-2 rounded-lg {{ $currentSection === 'popular' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white' }}">
                Films Populaires
            </a>
            <a href="{{ route('dashboard', ['section' => 'trending']) }}" 
               class="px-4 py-2 rounded-lg {{ $currentSection === 'trending' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white' }}">
                Films Tendance
            </a>
        </div>

        <!-- Grille de films -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($movies as $movie)
                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transform transition-transform hover:scale-105">
                    <a href="{{ route('movies.show', $movie['id']) }}">
                        @if($movie['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full h-auto">
                        @else
                            <div class="w-full h-100px bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">No Image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <h2 class="text-xl font-bold text-white hover:text-purple-500">
                                    {{ $movie['title'] }}
                                </h2>
                                @auth
                                    @php
                                        $isFavorite = Auth::user()->favorites()
                                            ->where('tmdb_id', $movie['id'])
                                            ->where('type', 'movie')
                                            ->exists();
                                    @endphp

                                    @if($isFavorite)
                                        <form action="{{ route('favorites.destroy') }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="tmdb_id" value="{{ $movie['id'] }}">
                                            <input type="hidden" name="type" value="movie">
                                            <button type="submit" class="text-purple-500 hover:text-purple-700 transition-colors duration-200">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                                </svg>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('favorites.store') }}" method="POST" class="inline">
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
                                @endauth
                            </div>
                            <div class="flex justify-between items-center mt-2">
                                <span class="text-purple-500 font-bold">
                                    {{ number_format($movie['vote_average'], 1) }}/10
                                </span>
                                <span class="text-gray-400">
                                    {{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}
                                </span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-400 py-10">
                    Aucun film disponible.
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $movies->links('vendor.pagination.tailwind') }}
        </div>
    </div>
</div>
@endsection
