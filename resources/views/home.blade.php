@extends('layouts.app')

@section('content')
<!-- Hero Section avec Vidéo -->
<div class="relative h-[65vh] sm:h-[calc(100vh-4rem)] bg-gray-900">
    <div class="absolute inset-0">
        <div class="relative w-full h-full">
            <iframe
                src="https://www.youtube.com/embed/LKFuXETZUsI?autoplay=1&mute=1&controls=0&loop=1&playlist=LKFuXETZUsI&showinfo=0"
                class="w-full h-full object-cover scale-110"
                style="aspect-ratio: 16/9; filter: brightness(0.9);"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen>
            </iframe>
        </div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full flex flex-col justify-end pb-8 sm:pb-12 z-20">
        <div class="max-w-xl backdrop-blur-md bg-gray-900/40 p-6 sm:p-8 rounded-2xl shadow-2xl relative overflow-hidden">
            <x-aos-wrapper animation="fade-up" duration="1000">
                <h1 class="text-2xl sm:text-4xl font-bold text-white/95 mb-3 tracking-wide">Vaiana : La Légende du bout du monde</h1>
                <p class="text-sm sm:text-base text-gray-300/90 mb-6 hidden sm:block line-clamp-2 leading-relaxed">
                    Une jeune fille intrépide se lance dans un voyage audacieux pour sauver son peuple. Au cours de sa traversée du vaste océan, Vaiana va rencontrer le légendaire demi-dieu Maui.
                </p>
                <div class="flex items-center gap-6">
                    <button onclick="window.location.href='{{ route('movies.show', 277834) }}'"
                       class="group bg-purple-600 hover:bg-purple-700 text-white px-6 py-2.5 rounded-xl flex items-center gap-2 text-sm sm:text-base transition-all duration-300 hover:shadow-lg hover:shadow-purple-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-300 group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="font-medium">Détails</span>
                    </button>
                    <div class="flex items-center gap-4">
                        <span class="flex items-center gap-1.5 text-yellow-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                            </svg>
                            <span class="text-white font-medium">7.6</span>
                        </span>
                        <span class="text-gray-400 text-sm">2016</span>
                    </div>
                </div>
            </x-aos-wrapper>
        </div>
    </div>
</div>

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