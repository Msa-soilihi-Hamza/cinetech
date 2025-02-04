@extends('layouts.app')

@section('content')
<!-- Section Bienvenue -->
<div class="bg-gray-900 py-16 text-center">
    <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Bienvenue chez <span class="text-purple-500">Cinetech</span></h1>
    <p class="text-xl text-gray-300 mb-8">Découvrez les meilleurs films et séries</p>
    <div class="flex justify-center gap-4">
        <a href="#films" class="bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg text-lg font-medium transition-all duration-300 scroll-smooth">
            Explorer les Films
        </a>
        <a href="#series" class="bg-gray-800 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-lg font-medium transition-all duration-300 scroll-smooth">
            Explorer les Séries
        </a>
    </div>
</div>

<!-- Hero Section -->
<div class="relative h-[65vh] sm:h-[calc(100vh-4rem)] bg-gray-900">
    <div class="absolute inset-0">
        <div class="relative w-full h-full">
            <iframe
                src="https://www.youtube.com/embed/LKFuXETZUsI?autoplay=1&mute=1&controls=0&loop=1&playlist=LKFuXETZUsI&showinfo=0"
                class="w-full h-full object-cover"
                style="aspect-ratio: 16/9;"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
            <!-- Overlay avec dégradé -->
            <div class="absolute inset-0 bg-gray-900/80"></div>
        </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 p-8 z-20">
        <div class="max-w-3xl">
            <div class="mb-2">
                <span class="bg-purple-500 text-white px-3 py-1 rounded-md text-sm font-medium">À l'affiche</span>
            </div>
            <h1 class="text-4xl sm:text-5xl font-bold text-white mb-4">Vaiana 2</h1>
            <p class="text-lg text-gray-200 mb-6 line-clamp-2 max-w-2xl">
                Après avoir reçu une invitation inattendue de ses ancêtres, Vaiana entreprend un périple qui la conduira jusqu'aux eaux dangereuses situées aux confins des mers du Pacifique.
            </p>
            <div class="flex items-center gap-4">
                <a href="{{ route('movies.show', 277834) }}" 
                   class="inline-flex items-center bg-purple-500 hover:bg-purple-600 text-white px-6 py-3 rounded-lg text-lg font-medium transition-all duration-300">
                    En savoir plus
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contenu existant -->
<div class="bg-gray-900 pt-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div id="films">
            <x-aos-wrapper animation="fade-down" duration="800">
                <h1 class="text-3xl font-bold text-white mb-6">Films</h1>
            </x-aos-wrapper>
            
            <div class="mb-8">
                @if(!empty($movies))
                    <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                        @foreach(array_chunk($movies->toArray(), 8) as $chunkIndex => $chunk)
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
                @endif
            </div>
        </div>

        <div id="series">
            <x-aos-wrapper animation="fade-down" duration="800">
                <h1 class="text-3xl font-bold text-white mb-8">Séries TV</h1>
            </x-aos-wrapper>
            
            @if(!empty($tvShows))
                <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
                    @foreach(array_chunk($tvShows->toArray(), 8) as $chunkIndex => $chunk)
                        <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 col-span-full" 
                             data-aos="fade-up"
                             data-aos-duration="800"
                             data-aos-delay="{{ $chunkIndex * 200 }}">
                            @foreach($chunk as $tvShow)
                                <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105">
                                    <a href="{{ route('tv.show', $tvShow['id']) }}">
                                        @if($tvShow['poster_path'])
                                            <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                                                 alt="{{ $tvShow['name'] }}"
                                                 class="w-full h-[250px] sm:h-[400px] object-cover"
                                                 loading="lazy">
                                        @endif
                                    </a>
                                    
                                    <div class="p-2 sm:p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <a href="{{ route('tv.show', $tvShow['id']) }}" class="block flex-1">
                                                <h2 class="text-sm sm:text-xl font-bold text-white hover:text-purple-500 break-words sm:truncate">
                                                    @if(strlen($tvShow['name']) > 15)
                                                        <span class="sm:hidden">{{ wordwrap($tvShow['name'], 15, "\n", true) }}</span>
                                                        <span class="hidden sm:inline">{{ $tvShow['name'] }}</span>
                                                    @else
                                                        {{ $tvShow['name'] }}
                                                    @endif
                                                </h2>
                                            </a>
                                            <div class="ml-2 transform scale-75 sm:scale-100">
                                                <x-favorite-button :id="$tvShow['id']" type="tv" />
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-purple-500 font-bold">{{ number_format($tvShow['vote_average'], 1) }}/10</span>
                                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($tvShow['first_air_date'])->format('Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
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

@push('styles')
<style>
.splide__track {
    height: calc(100vh - 4rem) !important;
}

.splide__list {
    height: 100% !important;
}

/* Styles pour les flèches de navigation */
.splide__arrow {
    background: rgba(255, 255, 255, 0.3) !important;
    width: 3em !important;
    height: 3em !important;
    opacity: 0.7;
    transition: all 0.3s ease;
}

.splide__arrow:hover {
    background: rgba(255, 255, 255, 0.5) !important;
    opacity: 1;
}

.splide__arrow svg {
    width: 1.5em !important;
    height: 1.5em !important;
    fill: #fff !important;
}
</style>
@endpush
@endsection 