@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-aos-wrapper animation="fade-down" duration="800">
            <h1 class="text-3xl font-bold text-white mb-8">Films</h1>
        </x-aos-wrapper>
        
        <x-aos-wrapper animation="fade-up" duration="800">
            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mb-12">
                @foreach($movies as $movie)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105"
                         data-aos="fade-up"
                         data-aos-duration="600"
                         data-aos-delay="{{ $loop->index * 50 }}">
                        <a href="{{ route('movies.show', $movie['id']) }}">
                            @if($movie['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                     alt="{{ $movie['title'] }}"
                                     class="w-full h-[400px] object-cover">
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
        </x-aos-wrapper>

        <x-aos-wrapper animation="fade-down" duration="800">
            <h1 class="text-3xl font-bold text-white mb-8">Séries TV</h1>
        </x-aos-wrapper>
        
        <x-aos-wrapper animation="fade-up" duration="800">
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($tvShows as $tvShow)
                    <div class="bg-gray-800 rounded-lg overflow-hidden shadow-lg transition-transform hover:scale-105"
                         data-aos="fade-up"
                         data-aos-duration="600"
                         data-aos-delay="{{ $loop->index * 50 }}">
                        <a href="{{ route('tv.show', $tvShow['id']) }}">
                            @if($tvShow['poster_path'])
                                <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                                     alt="{{ $tvShow['name'] }}"
                                     class="w-full h-[400px] object-cover">
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
        </x-aos-wrapper>
    </div>
</div>
@endsection 