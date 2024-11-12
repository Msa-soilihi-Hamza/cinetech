@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg overflow-hidden">
            <!-- Image de fond -->
            <div class="relative h-96">
                @if($tvShow['backdrop_path'])
                    <img src="https://image.tmdb.org/t/p/original{{ $tvShow['backdrop_path'] }}" 
                         alt="{{ $tvShow['name'] }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900"></div>
                @endif
            </div>
            
            <!-- Contenu -->
            <div class="relative -mt-32 px-8 pb-8">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Affiche -->
                    <div class="flex-shrink-0">
                        @if($tvShow['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                                 alt="{{ $tvShow['name'] }}"
                                 class="w-64 rounded-lg shadow-lg">
                        @endif
                    </div>
                    
                    <!-- Informations -->
                    <div class="text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $tvShow['name'] }}</h1>
                        
                        <!-- Métadonnées -->
                        <div class="flex items-center gap-4 mb-4">
                            <span class="text-purple-500 font-bold">{{ number_format($tvShow['vote_average'], 1) }}/10</span>
                            <span class="text-gray-400">{{ \Carbon\Carbon::parse($tvShow['first_air_date'])->format('d/m/Y') }}</span>
                            <span class="text-gray-400">
                                @foreach($tvShow['genres'] as $genre)
                                    {{ $genre['name'] }}@if(!$loop->last), @endif
                                @endforeach
                            </span>
                        </div>
                        
                        <!-- Synopsis -->
                        <div class="mb-8">
                            <h2 class="text-xl font-semibold mb-2">Synopsis</h2>
                            <p class="text-gray-300">{{ $tvShow['overview'] }}</p>
                        </div>
                        
                        <!-- Distribution -->
                        @if(isset($tvShow['credits']['cast']) && count($tvShow['credits']['cast']) > 0)
                            <div>
                                <h2 class="text-xl font-semibold mb-4">Distribution principale</h2>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach(array_slice($tvShow['credits']['cast'], 0, 4) as $actor)
                                        <div class="text-center">
                                            @if($actor['profile_path'])
                                                <img src="https://image.tmdb.org/t/p/w185{{ $actor['profile_path'] }}"
                                                     alt="{{ $actor['name'] }}"
                                                     class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                                            @endif
                                            <p class="font-medium">{{ $actor['name'] }}</p>
                                            <p class="text-sm text-gray-400">{{ $actor['character'] }}</p>
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
