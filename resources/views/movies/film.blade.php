@extends('layouts.app')

@section('content')
<style>
    .animated-bg {
        background: linear-gradient(180deg, #111827, #111827, #132241);
        background-size: 100% 600%;

        -webkit-animation: AnimationName 10s ease infinite;
        -moz-animation: AnimationName 10s ease infinite;
        -o-animation: AnimationName 10s ease infinite;
        animation: AnimationName 10s ease infinite;
    }

    @-webkit-keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
    @-moz-keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
    @-o-keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
    @keyframes AnimationName {
        0%{background-position:50% 0%}
        50%{background-position:50% 100%}
        100%{background-position:50% 0%}
    }
</style>

<div class="min-h-screen animated-bg py-8"
     x-data="{ 
        selectedGenre: '{{ request('genre') }}',
        async filterMovies(genre) {
            console.log('filterMovies appelé avec genre:', genre);
            this.selectedGenre = genre;
            try {
                console.log('Envoi de la requête fetch...');
                const response = await fetch(`/film?genre=${genre}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const html = await response.text();
                console.log('Réponse reçue, mise à jour du DOM...');
                document.querySelector('.movies-grid').innerHTML = html;
                
                window.history.pushState({}, '', `/film${genre ? `?genre=${genre}` : ''}`);
                
                AOS.refreshHard();
                
                setTimeout(() => {
                    AOS.init({
                        duration: 800,
                        once: false,
                        mirror: true,
                        offset: 50
                    });
                }, 100);
            } catch (error) {
                console.error('Erreur lors du filtrage:', error);
            }
        }
     }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête avec titre -->
        

        <!-- Menu déroulant pour mobile -->
        <div class="sm:hidden mb-8">
            <select 
                class="w-full px-4 py-2 text-sm rounded-md text-white bg-gray-800 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500"
                x-model="selectedGenre"
                x-on:change="filterMovies($event.target.value)"
                x-init="$el.value = selectedGenre">
                <option value="">{{ __('Tous les genres') }}</option>
                @foreach($genres as $genre)
                    <option value="{{ $genre['id'] }}">{{ $genre['name'] }}</option>
                @endforeach
            </select>
        </div>

        <!-- Boutons de filtre pour desktop -->
        <x-aos-wrapper animation="fade-down" duration="800">
            <div class="hidden sm:flex justify-center flex-wrap gap-2 mb-8">
                <button 
                    @click="filterMovies('')"
                    :class="!selectedGenre ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                    Tout voir
                </button>
                @foreach($genres as $genre)
                    <button 
                        @click="filterMovies('{{ $genre['id'] }}')"
                        :class="selectedGenre === '{{ $genre['id'] }}' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                        {{ $genre['name'] }}
                    </button>
                @endforeach
            </div>
        </x-aos-wrapper>

        <!-- Grille de films -->
        <div class="movies-grid">
            @include('movies._filtered-grid', ['movies' => $movies])
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialisation d'AOS
    AOS.init({
        duration: 800,
        once: false,
        mirror: true,
        offset: 50
    });

    // Récupérer le genre depuis l'URL au chargement
    const urlParams = new URLSearchParams(window.location.search);
    const genre = urlParams.get('genre') || '';
    if (genre) {
        Alpine.store('movies', {
            selectedGenre: genre
        });
    }

    // Debug des genres disponibles
    console.log('Genres disponibles:', @json($genres));
});
</script>
@endpush
@endsection
