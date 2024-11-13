@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <!-- Image de fond -->
            <div class="relative h-96">
                @if(isset($tvShow['backdrop_path']) && $tvShow['backdrop_path'])
                    <img src="https://image.tmdb.org/t/p/original{{ $tvShow['backdrop_path'] }}"
                         alt="{{ $tvShow['title'] ?? '' }}"
                         class="w-full h-600px bg-gray-700 flex items-center justify-center">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900"></div>
                @endif
            </div>
            
            <!-- Contenu -->
            <div class="relative -mt-32 px-8 pb-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Affiche -->
                    <div class="flex-shrink-0">
                        @if(isset($tvShow['poster_path']) && $tvShow['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                                 alt="{{ $tvShow['title'] ?? '' }}"
                                 class="w-full h-600px bg-gray-700 flex items-center justify-center">
                        @endif
                    </div>
                    
                    <!-- Informations -->
                    <div class="text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $tvShow['title'] ?? '' }}</h1>
                        
                        <!-- Métadonnées -->
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-purple-500 font-bold">{{ number_format($tvShow['vote_average'] ?? 0, 1) }}/10</span>
                            <span class="text-gray-400">{{ isset($tvShow['release_date']) ? \Carbon\Carbon::parse($tvShow['release_date'])->format('d/m/Y') : '' }}</span>
                            <span class="text-gray-400">
                                @if(isset($tvShow['genres']))
                                    @foreach($tvShow['genres'] as $genre)
                                        {{ $genre['name'] }}@if(!$loop->last), @endif
                                    @endforeach
                                @endif
                            </span>
                        </div>
                        
                        <!-- Synopsis -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-2">Synopsis</h2>
                            <p class="text-gray-300">{{ $tvShow['overview'] ?? 'Aucun synopsis disponible.' }}</p>
                        </div>
                        
                        <!-- Distribution -->
                        @if(isset($tvShow['credits']['cast']) && count($tvShow['credits']['cast']) > 0)
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Distribution principale</h2>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                                    @foreach(array_slice($tvShow['credits']['cast'], 0, 4) as $actor)
                                        <div class="text-center bg-gray-700 rounded-lg p-6 hover:bg-gray-600 transition">
                                            @if($actor['profile_path'])
                                                <img src="https://image.tmdb.org/t/p/original{{ $actor['profile_path'] }}"
                                                     alt="{{ $actor['name'] }}"
                                                     class="w-[400px] h-[400px] rounded-full mx-auto mb-4 object-cover">
                                            @endif
                                            <p class="font-medium text-xl text-white">{{ $actor['name'] }}</p>
                                            <p class="text-base text-gray-300">{{ $actor['character'] }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
