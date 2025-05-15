@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            {{-- Image de fond --}}
            <div class="relative h-100">
                @if(isset($movie->backdrop_path) && $movie->backdrop_path)
                    <img src="https://image.tmdb.org/t/p/original{{ $movie->backdrop_path }}"
                         alt="{{ $movie->title ?? '' }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="relative -mt-32 px-6 md:px-12 pb-12">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- Affiche --}}
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        @if(isset($movie->poster_path))
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie->poster_path }}"
                                 alt="{{ $movie->title }}"
                                 class="w-full rounded-lg shadow-xl">
                        @endif
                    </div>

                    {{-- Informations --}}
                    <div class="flex-grow text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $movie->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span class="text-purple-500 font-bold text-xl">
                                {{ number_format($movie->vote_average, 1) }}/10
                            </span>
                            @if(isset($movie->release_date))
                                <span class="text-gray-400">
                                    {{ \Carbon\Carbon::parse($movie->release_date)->format('d/m/Y') }}
                                </span>
                            @endif
                            @if(isset($movie->genres))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($movie->genres as $genre)
                                        <span class="px-3 py-1 bg-gray-700 rounded-full text-sm">
                                            {{ $genre['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center gap-4 mb-6">
                            <x-movie-favorite-button :id="$movie->id" />
                        </div>

                        @if(isset($movie->overview))
                            <p class="text-gray-300 leading-relaxed mb-8">{{ $movie->overview }}</p>
                        @endif

                        <div class="flex flex-wrap gap-4 mb-6">
                            <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-sm">
                                {{ $movie->runtime }} min
                            </span>
                        </div>

                        <div class="flex flex-wrap gap-3 mb-6">
                            @foreach($movie->genres as $genre)
                                <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full text-sm">
                                    {{ $genre['name'] }}
                                </span>
                            @endforeach
                        </div>

                        @if(auth()->check())
                            {{-- <form action="{{ route('favorites.store') }}" method="POST" class="flex-1">
                                @csrf
                                <input type="hidden" name="media_type" value="movie">
                                <input type="hidden" name="media_id" value="{{ $movie->id }}">
                                <button type="submit" 
                                        class="w-full bg-purple-500 text-white px-4 py-2 rounded-lg hover:bg-purple-600 transition-colors">
                                    Ajouter aux favoris
                                </button>
                            </form> --}}
                        @endif
                    </div>
                </div>

                {{-- Bandes-annonces --}}
                @if($trailers->count() > 0)
                    <div class="mt-12">
                        <h2 class="text-2xl font-bold mb-6">Bandes-annonces</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($trailers as $trailer)
                                <div class="relative group">
                                    <div class="aspect-w-16 aspect-h-9 bg-gray-800 rounded-lg overflow-hidden">
                                        <img src="https://img.youtube.com/vi/{{ $trailer['key'] }}/mqdefault.jpg"
                                             alt="{{ $movie->title }} - Bande-annonce"
                                             class="w-full h-full object-cover">
                                    </div>
                                    <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                                        <a href="https://www.youtube.com/watch?v={{ $trailer['key'] }}" 
                                           target="_blank"
                                           class="text-white text-3xl">
                                            <i class="fas fa-play"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Distribution --}}
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-6">Distribution</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if(isset($movie->credits['cast']))
                            @foreach(array_slice($movie->credits['cast'], 0, 8) as $actor)
                                <div class="bg-gray-800 rounded-lg p-4">
                                    <h3 class="font-semibold mb-2">{{ $actor['name'] }}</h3>
                                    <p class="text-gray-400 mb-2">{{ $actor['character'] ?? 'Non spécifié' }}</p>
                                    @if(isset($actor['profile_path']))
                                        <img src="https://image.tmdb.org/t/p/w185{{ $actor['profile_path'] }}"
                                             alt="{{ $actor['name'] }}"
                                             class="w-24 h-24 rounded-full object-cover mt-2">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gray-700 flex items-center justify-center mt-2">
                                            <span class="text-gray-400">Aucune image</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-400">Aucune information sur la distribution disponible.</p>
                        @endif
                    </div>
                </div>

                {{-- Équipe technique --}}
                <div class="mt-12">
                    <h2 class="text-2xl font-bold mb-6">Équipe technique</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if(isset($movie->credits['crew']))
                            @foreach(array_slice($movie->credits['crew'], 0, 8) as $crewMember)
                                <div class="bg-gray-800 rounded-lg p-4">
                                    <h3 class="font-semibold mb-2">{{ $crewMember['name'] }}</h3>
                                    <p class="text-gray-400 mb-2">{{ $crewMember['job'] ?? 'Non spécifié' }}</p>
                                    @if(isset($crewMember['profile_path']))
                                        <img src="https://image.tmdb.org/t/p/w185{{ $crewMember['profile_path'] }}"
                                             alt="{{ $crewMember['name'] }}"
                                             class="w-24 h-24 rounded-full object-cover mt-2">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-gray-700 flex items-center justify-center mt-2">
                                            <span class="text-gray-400">Aucune image</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <p class="text-gray-400">Aucune information sur l'équipe technique disponible.</p>
                        @endif
                    </div>
                </div>
                
                {{-- Section des commentaires --}}
                <x-comments :mediaType="$mediaType" :mediaId="$mediaId" :comments="$comments" />
            </div>
        </div>
    </div>
</div>

<script>
    // Ajouter un gestionnaire d'événements pour les mentions @
    document.addEventListener('DOMContentLoaded', function() {
        const textareas = document.querySelectorAll('textarea');
        
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function(e) {
                const value = this.value;
                const lastWord = value.split(' ').pop();
                
                if (lastWord.startsWith('@')) {
                    // Ici vous pouvez ajouter une logique pour afficher les suggestions d'utilisateurs
                    // Pour le moment, nous afficherons simplement un message de démonstration
                    console.log('Mention détectée:', lastWord);
                }
            });
        });
    });
</script>

@endsection