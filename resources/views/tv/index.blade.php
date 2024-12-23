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
        async filterShows(genre) {
            console.log('filterShows appelé avec genre:', genre);
            this.selectedGenre = genre;
            try {
                console.log('Envoi de la requête fetch...');
                const response = await fetch(`/tv?genre=${genre}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const html = await response.text();
                console.log('Réponse reçue, mise à jour du DOM...');
                document.querySelector('.shows-grid').innerHTML = html;
                
                window.history.pushState({}, '', `/tv${genre ? `?genre=${genre}` : ''}`);
                
                AOS.init({
                    duration: 800,
                    once: false,
                    mirror: true,
                    offset: 50
                });
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
                x-on:change="filterShows($event.target.value)"
                x-init="$el.value = selectedGenre">
                <option value="">{{ __('Tous les genres') }}</option>
                @foreach($genres as $key => $id)
                    <option value="{{ $key }}">{{ ucfirst(str_replace('_', ' ', $key)) }}</option>
                @endforeach
            </select>
        </div>

        <!-- Boutons de filtre pour desktop -->
        <x-aos-wrapper animation="fade-down" duration="800">
            <div class="hidden sm:flex justify-center flex-wrap gap-2 mb-8">
                <button 
                    @click="filterShows('')"
                    :class="!selectedGenre ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                    class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                    Tout voir
                </button>

                @foreach($genres as $key => $id)
                    <button 
                        @click="filterShows('{{ $key }}')"
                        :class="selectedGenre === '{{ $key }}' ? 'bg-purple-600 text-white' : 'bg-gray-800 text-gray-300 hover:bg-purple-600 hover:text-white'"
                        class="px-4 py-2 rounded-full text-sm font-medium transition-colors duration-200">
                        {{ ucfirst(str_replace('_', ' ', $key)) }}
                    </button>
                @endforeach
            </div>
        </x-aos-wrapper>

        @if(empty($shows))
            <div class="text-white">Aucune série n'a été trouvée.</div>
        @endif

        <div class="shows-grid">
            @if(!empty($shows))
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach($shows->chunk(8) as $chunkIndex => $chunk)
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 col-span-full" 
                             data-aos="fade-up"
                             data-aos-duration="800"
                             data-aos-delay="{{ $chunkIndex * 200 }}">
                            @foreach($chunk as $show)
                                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                                    <a href="{{ route('tv.show', $show['id']) }}">
                                        @if($show['poster_path'])
                                            <img src="https://image.tmdb.org/t/p/w500{{ $show['poster_path'] }}"
                                                 alt="{{ $show['name'] }}"
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
                                            <a href="{{ route('tv.show', $show['id']) }}" class="block flex-1">
                                                <h2 class="text-sm sm:text-xl font-bold text-white hover:text-purple-500 break-words sm:truncate">
                                                    @if(strlen($show['name']) > 15)
                                                        <span class="sm:hidden">{{ wordwrap($show['name'], 15, "\n", true) }}</span>
                                                        <span class="hidden sm:inline">{{ $show['name'] }}</span>
                                                    @else
                                                        {{ $show['name'] }}
                                                    @endif
                                                </h2>
                                            </a>
                                            <div class="ml-2 transform scale-75 sm:scale-100">
                                                <x-favorite-button :id="$show['id']" type="tv" />
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-purple-500 font-bold">{{ number_format($show['vote_average'], 1) }}/10</span>
                                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($show['first_air_date'])->format('Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>

                @if($shows->hasPages())
                    <div class="mt-8 flex justify-center">
                        {{ $shows->links() }}
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