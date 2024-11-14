@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-white mb-8">Résultats pour "{{ $query }}"</h2>

        @if(isset($error))
            <div class="bg-red-500 text-white p-4 rounded-lg mb-8">
                {{ $error }}
            </div>
        @endif

        @if(count($results) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($results as $result)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                        @if(isset($result['poster_path']) && $result['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $result['poster_path'] }}"
                                 alt="{{ $result['title'] ?? 'Titre non disponible' }}"
                                 class="w-full h-auto object-cover">
                        @else
                            <div class="w-full h-96 bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-400">Image non disponible</span>
                            </div>
                        @endif
                        
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-white">
                                    {{ $result['title'] ?? $result['name'] ?? 'Titre non disponible' }}
                                </h3>
                                @if(isset($result['media_type']))
                                    <span class="text-sm text-gray-400 ml-2">
                                        {{ $result['media_type'] === 'movie' ? 'Film' : 'Série' }}
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-purple-500 font-bold">
                                    {{ number_format($result['vote_average'] ?? 0, 1) }}/10
                                </span>
                                @if(isset($result['release_date']) && $result['release_date'])
                                    <span class="text-gray-400">
                                        {{ \Carbon\Carbon::parse($result['release_date'])->format('Y') }}
                                    </span>
                                @endif
                            </div>

                            @auth
                                <div class="mt-4 flex justify-end">
                                    <button class="favorite-button text-gray-400 hover:text-red-500 transition-colors duration-200"
                                            data-type="{{ $result['media_type'] ?? 'movie' }}"
                                            data-id="{{ $result['id'] ?? '' }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                        </svg>
                                    </button>
                                </div>
                            @endauth
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center text-gray-400 py-10">
                Aucun résultat trouvé pour "{{ $query }}"
            </div>
        @endif
    </div>
</div>
@endsection 