@extends('layouts.app')

@section('content')
<!-- Hero Section avec Splide -->
<div class="splide relative bg-gray-900" id="main-slider">
    <div class="splide__track h-screen">
        <div class="splide__list">
            <!-- Slide 1 - Les Gardiens de la Galaxie -->
            <div class="splide__slide relative h-[calc(100vh-4rem)]">
                <div class="absolute inset-0">
                    <img src="https://image.tmdb.org/t/p/original/5YZbUmjbMa3ClvSW1Wj3D6XGolb.jpg" 
                         alt="Guardians of the Galaxy Banner" 
                         class="w-full h-full object-cover object-[center_20%] opacity-50">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/50 to-transparent"></div>
                </div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-3xl">
                        <x-aos-wrapper animation="fade-right" duration="1000">
                            <h1 class="text-5xl font-bold text-white mb-4">Les Gardiens de la Galaxie Vol. 3</h1>
                            <p class="text-xl text-gray-300 mb-6">
                                Peter Quill, encore sous le choc d'une terrible perte, doit rallier son équipe pour une mission dangereuse visant à sauver Rocket.
                            </p>
                            <div class="flex items-center gap-4">
                                <button class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-full flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Lecture
                                </button>
                                <x-favorite-button :id="447365" type="movie" />
                            </div>
                        </x-aos-wrapper>
                    </div>
                </div>
            </div>

            <!-- Slide 2 - Super Mario Bros -->
            <div class="splide__slide relative h-[calc(100vh-4rem)]">
                <div class="absolute inset-0">
                    <img src="https://image.tmdb.org/t/p/original/9n2tJBplPbgR2ca05hS5CKXwP2c.jpg" 
                         alt="Super Mario Bros Banner" 
                         class="w-full h-full object-cover object-[center_20%] opacity-50">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/50 to-transparent"></div>
                </div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-3xl">
                        <x-aos-wrapper animation="fade-right" duration="1000">
                            <h1 class="text-5xl font-bold text-white mb-4">Super Mario Bros. Le Film</h1>
                            <p class="text-xl text-gray-300 mb-6">
                                Un plombier nommé Mario parcourt un labyrinthe souterrain avec son frère, Luigi, essayant de sauver une princesse capturée.
                            </p>
                            <div class="flex items-center gap-4">
                                <button class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-full flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Lecture
                                </button>
                                <x-favorite-button :id="502356" type="movie" />
                            </div>
                        </x-aos-wrapper>
                    </div>
                </div>
            </div>

            <!-- Slide 3 - Avatar 2 -->
            <div class="splide__slide relative h-[calc(100vh-4rem)]">
                <div class="absolute inset-0">
                    <img src="https://image.tmdb.org/t/p/original/198vrF8k7mfQ4FjDJsBmdQcaiyq.jpg" 
                         alt="Avatar 2 Banner" 
                         class="w-full h-full object-cover object-[center_20%] opacity-50">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-900/90 via-gray-900/50 to-transparent"></div>
                </div>
                <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex items-center">
                    <div class="max-w-3xl">
                        <x-aos-wrapper animation="fade-right" duration="1000">
                            <h1 class="text-5xl font-bold text-white mb-4">Avatar : La Voie de l'eau</h1>
                            <p class="text-xl text-gray-300 mb-6">
                                Jake Sully et Ney'tiri ont formé une famille et font tout pour rester aussi soudés que possible. Ils sont cependant contraints de quitter leur foyer et d'explorer les différentes régions de Pandora.
                            </p>
                            <div class="flex items-center gap-4">
                                <button class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-full flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Lecture
                                </button>
                                <x-favorite-button :id="76600" type="movie" />
                            </div>
                        </x-aos-wrapper>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var splide = new Splide('#main-slider', {
        type: 'fade',
        rewind: true,
        autoplay: true,
        interval: 5000,
        arrows: true,
        pagination: false,
        video: {
            loop: true,
        },
    });

    splide.mount();
});
</script>
@endpush

<!-- Contenu existant -->
<div class="bg-gray-900 pt-2">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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
                                                 class="w-full h-[400px] object-cover"
                                                 loading="lazy">
                                        @endif
                                    </a>
                                    
                                    <div class="p-4">
                                        <div class="flex justify-between items-center mb-2">
                                            <a href="{{ route('movies.show', $movie['id']) }}" class="block">
                                                <h2 class="text-xl font-bold text-white hover:text-purple-500">{{ $movie['title'] }}</h2>
                                            </a>
                                            <x-favorite-button :id="$movie['id']" type="movie" />
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
                                             class="w-full h-[400px] object-cover"
                                             loading="lazy">
                                    @endif
                                </a>
                                
                                <div class="p-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <a href="{{ route('tv.show', $tvShow['id']) }}" class="block">
                                            <h2 class="text-xl font-bold text-white hover:text-purple-500">{{ $tvShow['name'] }}</h2>
                                        </a>
                                        <x-favorite-button :id="$tvShow['id']" type="tv" />
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