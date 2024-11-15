@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-3xl font-bold text-white mb-8">Mes Favoris</h1>

        @if(isset($error))
            <div class="text-red-500 mb-4">{{ $error }}</div>
        @endif

        @if($favorites->isEmpty())
            <div class="text-center text-gray-400 py-10">
                Vous n'avez pas encore de favoris.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($favorites as $favorite)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg">
                        @if($favorite['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $favorite['poster_path'] }}"
                                 alt="{{ $favorite['title'] }}"
                                 class="w-full h-auto object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">Image non disponible</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-white truncate">{{ $favorite['title'] }}</h3>
                                <form action="{{ route('favorites.remove') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="tmdb_id" value="{{ $favorite['tmdb_id'] }}">
                                    <button type="submit" class="text-red-500 hover:text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-purple-500 font-bold">{{ $favorite['vote_average'] }}/10</span>
                                <span class="text-gray-400">{{ $favorite['type'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection 