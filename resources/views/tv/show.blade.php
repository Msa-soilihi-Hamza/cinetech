@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            <!-- Image de fond -->
            <div class="relative h-100">
                @if(isset($tvShow['backdrop_path']) && $tvShow['backdrop_path'])
                    <img src="https://image.tmdb.org/t/p/original{{ $tvShow['backdrop_path'] }}"
                         alt="{{ $tvShow['title'] ?? '' }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                @endif
            </div>

            <!-- Contenu -->
            <div class="relative -mt-32 px-6 md:px-12 pb-12">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Affiche -->
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        @if(isset($tvShow['poster_path']) && $tvShow['poster_path'])
                            <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                                 alt="{{ $tvShow['title'] ?? '' }}"
                                 class="w-[400px] h-[500px] object-cover rounded-lg shadow-md">
                        @endif
                    </div>

                    <!-- Informations -->
                    <div class="text-white w-full md:w-2/3">
                        <h1 class="text-4xl font-extrabold mb-4 text-transparent bg-clip-text bg-gradient-to-r from-purple-500 to-blue-500">
                            {{ $tvShow['title'] ?? '' }}
                        </h1>

                        <!-- Métadonnées -->
                        <div class="flex items-center gap-6 mb-6 text-lg">
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
                            <h2 class="text-xl font-semibold mb-2 text-white">Synopsis</h2>
                            <p class="text-gray-300 text-base leading-relaxed">{{ $tvShow['overview'] ?? 'Aucun synopsis disponible.' }}</p>
                        </div>

                        <!-- Distribution -->
                        @if(isset($tvShow['credits']['cast']) && count($tvShow['credits']['cast']) > 0)
                            <div>
                                <h2 class="text-xl font-semibold mb-4 text-white">Distribution principale</h2>
                                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-6">
                                    @foreach(array_slice($tvShow['credits']['cast'], 0, 4) as $actor)
                                        <div class="text-center bg-gray-700 rounded-lg p-8 hover:bg-gray-600 transition-all duration-300">
                                            @if($actor['profile_path'])
                                                <img src="https://image.tmdb.org/t/p/original{{ $actor['profile_path'] }}"
                                                     alt="{{ $actor['name'] }}"
                                                     class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-md">
                                            @endif
                                            <p class="font-medium text-xl text-white">{{ $actor['name'] }}</p>
                                            <p class="text-sm text-gray-300">{{ $actor['character'] }}</p>
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
