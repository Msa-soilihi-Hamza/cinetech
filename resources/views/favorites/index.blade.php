@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Boutons de navigation -->
        <div class="flex justify-center gap-4 mb-8">
            <a href="#films" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm3 2h6v4H7V5zm8 8v2h1v-2h-1zm-2-2H7v4h6v-4zm2 0h1V9h-1v2zm1-4V5h-1v2h1zM5 5v2H4V5h1zm0 4H4v2h1V9zm-1 4h1v2H4v-2z" clip-rule="evenodd" />
                </svg>
                Films Favoris
            </a>
            <a href="#series" 
               class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg transition-colors flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd" />
                </svg>
                Séries Favorites
            </a>
        </div>

        <!-- En-tête Films -->
        <div id="films" class="mb-8 scroll-mt-8">
            <h1 class="text-3xl font-bold text-white mb-4">Films Favoris</h1>
            
            @if($favorites->where('type', 'movie')->isEmpty())
                <div class="bg-gray-800 rounded-lg p-8 text-center mb-12">
                    <p class="text-gray-400 text-lg">Vous n'avez pas encore de films favoris.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mb-12">
                    @foreach($favorites->where('type', 'movie') as $favorite)
                        <div id="favorite-{{ $favorite['tmdb_id'] }}" 
                             class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                            <a href="{{ route('movies.show', ['id' => $favorite['tmdb_id']]) }}" class="block">
                                @if($favorite['poster_path'])
                                    <img src="https://image.tmdb.org/t/p/w500{{ $favorite['poster_path'] }}"
                                         alt="{{ $favorite['title'] }}"
                                         class="w-full h-[250px] sm:h-[400px] object-cover"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-[250px] sm:h-[400px] bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-400">Image non disponible</span>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="p-2 sm:p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-sm sm:text-lg font-bold text-white break-words sm:truncate hover:text-purple-500" title="{{ $favorite['title'] }}">
                                        @if(strlen($favorite['title']) > 15)
                                            <span class="sm:hidden">{{ wordwrap($favorite['title'], 15, "\n", true) }}</span>
                                            <span class="hidden sm:inline">{{ $favorite['title'] }}</span>
                                        @else
                                            {{ $favorite['title'] }}
                                        @endif
                                    </h3>
                                    <form action="{{ route('favorites.destroy') }}"
                                          method="POST"
                                          class="favorite-form"
                                          data-id="{{ $favorite['tmdb_id'] }}"
                                          onsubmit="removeFavorite(event, {{ $favorite['tmdb_id'] }})">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tmdb_id" value="{{ $favorite['tmdb_id'] }}">
                                        <input type="hidden" name="type" value="movie">
                                        <button type="submit"
                                                class="text-purple-500 hover:text-purple-700 transition-colors"
                                                title="Retirer des favoris">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-purple-500 font-bold">{{ $favorite['vote_average'] }}/10</span>
                                    <span class="text-gray-400">{{ \Carbon\Carbon::parse($favorite['release_date'])->format('Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- En-tête Séries -->
        <div id="series" class="scroll-mt-8">
            <h1 class="text-3xl font-bold text-white mb-4">Séries TV Favorites</h1>
            
            @if($favorites->where('type', 'tv')->isEmpty())
                <div class="bg-gray-800 rounded-lg p-8 text-center">
                    <p class="text-gray-400 text-lg">Vous n'avez pas encore de séries favorites.</p>
                </div>
            @else
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($favorites->where('type', 'tv') as $favorite)
                        <div id="favorite-{{ $favorite['tmdb_id'] }}" 
                             class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                            <a href="{{ route('tv.show', ['id' => $favorite['tmdb_id']]) }}" class="block">
                                @if($favorite['poster_path'])
                                    <img src="https://image.tmdb.org/t/p/w500{{ $favorite['poster_path'] }}"
                                         alt="{{ $favorite['title'] }}"
                                         class="w-full h-[250px] sm:h-[400px] object-cover"
                                         loading="lazy">
                                @else
                                    <div class="w-full h-[250px] sm:h-[400px] bg-gray-700 flex items-center justify-center">
                                        <span class="text-gray-400">Image non disponible</span>
                                    </div>
                                @endif
                            </a>
                            
                            <div class="p-2 sm:p-4">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-sm sm:text-lg font-bold text-white break-words sm:truncate hover:text-purple-500" title="{{ $favorite['title'] }}">
                                        @if(strlen($favorite['title']) > 15)
                                            <span class="sm:hidden">{{ wordwrap($favorite['title'], 15, "\n", true) }}</span>
                                            <span class="hidden sm:inline">{{ $favorite['title'] }}</span>
                                        @else
                                            {{ $favorite['title'] }}
                                        @endif
                                    </h3>
                                    <form action="{{ route('favorites.destroy') }}"
                                          method="POST"
                                          class="favorite-form"
                                          data-id="{{ $favorite['tmdb_id'] }}"
                                          onsubmit="removeFavorite(event, {{ $favorite['tmdb_id'] }})">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="tmdb_id" value="{{ $favorite['tmdb_id'] }}">
                                        <input type="hidden" name="type" value="tv">
                                        <button type="submit"
                                                class="text-purple-500 hover:text-purple-700 transition-colors"
                                                title="Retirer des favoris">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-purple-500 font-bold">{{ $favorite['vote_average'] }}/10</span>
                                    <span class="text-gray-400">{{ \Carbon\Carbon::parse($favorite['release_date'])->format('Y') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        @if($favorites->isEmpty())
            <div class="mt-8 text-center">
                <a href="{{ route('all.media') }}" class="inline-block bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                    Découvrir des films et séries
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Bouton retour en haut -->
<button id="back-to-top" 
        class="fixed bottom-20 right-4 bg-purple-600 text-white p-2 rounded-full shadow-lg cursor-pointer opacity-0 transition-opacity duration-300 hover:bg-purple-700"
        style="display: none;">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
    </svg>
</button>

@endsection 

@push('scripts')
<script src="{{ asset('js/removeFavorite.js') }}"></script>

<script>
// Gestion du bouton retour en haut
document.addEventListener('DOMContentLoaded', function() {
    const backToTopButton = document.getElementById('back-to-top');
    
    // Afficher/masquer le bouton en fonction du scroll
    window.addEventListener('scroll', function() {
        if (window.scrollY > 300) {
            backToTopButton.style.display = 'block';
            setTimeout(() => backToTopButton.style.opacity = '1', 50);
        } else {
            backToTopButton.style.opacity = '0';
            setTimeout(() => backToTopButton.style.display = 'none', 300);
        }
    });

    // Action de retour en haut au clic
    backToTopButton.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
</script>

<style>
.bg-gray-800 {
    transition: opacity 0.3s ease, transform 0.3s ease;
}

html {
    scroll-behavior: smooth;
}

.scroll-mt-8 {
    scroll-margin-top: 2rem;
}

#back-to-top {
    z-index: 50;
}
</style>
@endpush 