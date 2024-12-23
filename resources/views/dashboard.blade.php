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
        async filterMovies(genreId) {
            console.log('filterMovies appelé avec genre:', genreId);
            this.selectedGenre = genreId;
            try {
                console.log('Envoi de la requête fetch...');
                const response = await fetch(`/dashboard?genre=${genreId}`, {
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
                
                window.history.pushState({}, '', `/dashboard${genreId ? `?genre=${genreId}` : ''}`);
                
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
                @foreach($genres as $genre)
                    <button 
                        @click="filterMovies('{{ $genre['id'] }}')"
                        :class="selectedGenre === '{{ $genre['id'] }}' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                        class="px-4 py-2 rounded-full text-sm">
                        {{ $genre['name'] }}
                    </button>
                @endforeach
                <button 
                    @click="filterMovies('')"
                    :class="!selectedGenre ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                    class="px-4 py-2 rounded-full text-sm">
                    Tout voir
                </button>
            </div>
        </x-aos-wrapper>

        <div class="movies-grid">
            @if(!empty($movies))
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($movies->chunk(8) as $chunkIndex => $chunk)
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 col-span-full" 
                             data-aos="fade-up"
                             data-aos-duration="800"
                             data-aos-delay="{{ $chunkIndex * 200 }}">
                            @foreach($chunk as $movie)
                                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                                    <a href="{{ route('movies.show', $movie['id']) }}">
                                        @if($movie['poster_path'])
                                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                                 alt="{{ $movie['title'] }}"
                                                 class="w-full h-[250px] sm:h-[400px] object-cover"
                                                 loading="lazy">
                                        @else
                                            <div class="w-full h-[250px] sm:h-[400px] bg-gray-700 flex items-center justify-center">
                                                <span class="text-gray-400">Image non disponible</span>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <div class="p-2 sm:p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <a href="{{ route('movies.show', $movie['id']) }}" class="block flex-1">
                                                <h2 class="text-sm sm:text-xl font-bold text-white hover:text-purple-500 break-words sm:truncate">
                                                    @if(strlen($movie['title']) > 15)
                                                        <span class="sm:hidden">{{ wordwrap($movie['title'], 15, "\n", true) }}</span>
                                                        <span class="hidden sm:inline">{{ $movie['title'] }}</span>
                                                    @else
                                                        {{ $movie['title'] }}
                                                    @endif
                                                </h2>
                                            </a>
                                            <div class="ml-2 transform scale-75 sm:scale-100">
                                                <x-favorite-button :id="$movie['id']" type="movie" />
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-purple-500 font-bold">{{ number_format($movie['vote_average'], 1) }}/10</span>
                                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($movie['release_date'])->format('Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if($movies->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $movies->links() }}
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    AOS.init({
        duration: 800,
        once: false,
        mirror: true,
        offset: 50
    });
});
</script>
@endpush
@endsection
