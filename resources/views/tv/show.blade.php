@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            {{-- Image de fond --}}
            <div class="relative h-100">
                @if(isset($tvShow['backdrop_path']) && $tvShow['backdrop_path'])
                    <img src="https://image.tmdb.org/t/p/original{{ $tvShow['backdrop_path'] }}"
                         alt="{{ $tvShow['name'] ?? '' }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="relative -mt-32 px-6 md:px-12 pb-12">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- Affiche --}}
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        @if(isset($tvShow['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w500{{ $tvShow['poster_path'] }}"
                                 alt="{{ $tvShow['name'] }}"
                                 class="w-full rounded-lg shadow-xl">
                        @endif
                    </div>

                    {{-- Informations --}}
                    <div class="flex-grow text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $tvShow['name'] }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span class="text-purple-500 font-bold text-xl">
                                {{ number_format($tvShow['vote_average'], 1) }}/10
                            </span>
                            @if(isset($tvShow['first_air_date']))
                                <span class="text-gray-400">
                                    {{ \Carbon\Carbon::parse($tvShow['first_air_date'])->format('d/m/Y') }}
                                </span>
                            @endif
                            @if(isset($tvShow['genres']))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($tvShow['genres'] as $genre)
                                        <span class="px-3 py-1 bg-gray-700 rounded-full text-sm">
                                            {{ $genre['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(isset($tvShow['overview']))
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold mb-2">Synopsis</h2>
                                <p class="text-gray-300 leading-relaxed">{{ $tvShow['overview'] }}</p>
                            </div>
                        @endif

                        @if(isset($tvShow['credits']['cast']))
                            <div class="cast-section">
                                <h2 class="text-xl font-semibold mb-4">Distribution principale</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach(array_slice($tvShow['credits']['cast'], 0, 8) as $actor)
                                        <div class="bg-gray-700 p-4 rounded-lg text-center">
                                            @if(isset($actor['profile_path']))
                                                <img src="https://image.tmdb.org/t/p/w185{{ $actor['profile_path'] }}"
                                                     alt="{{ $actor['name'] }}"
                                                     class="w-24 h-24 rounded-full mx-auto object-cover mb-3">
                                            @endif
                                            <p class="font-semibold">{{ $actor['name'] }}</p>
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

        {{-- Section commentaires --}}
        <div class="mt-8 bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="container mx-auto">
                <h2 class="text-2xl font-bold text-white mb-6">Commentaires</h2>

                {{-- Formulaire d'ajout de commentaire --}}
                @auth
                    <form action="{{ route('comments.store') }}" method="POST" class="mb-8">
                        @csrf
                        <input type="hidden" name="media_type" value="tv">
                        <input type="hidden" name="media_id" value="{{ $tvShow['id'] }}">
                        
                        <div class="mb-4">
                            <textarea 
                                name="content" 
                                rows="3" 
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500"
                                placeholder="Ajouter un commentaire..."></textarea>
                        </div>
                        
                        <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Publier
                        </button>
                    </form>
                @else
                    <div class="text-center mb-8">
                        <p class="text-gray-300">
                            <a href="{{ route('login') }}" class="text-purple-500 hover:underline">Connectez-vous</a> 
                            pour laisser un commentaire
                        </p>
                    </div>
                @endauth

                {{-- Messages de succès/erreur --}}
                @if(session('success'))
                    <div class="bg-green-500 text-white p-4 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Liste des commentaires --}}
                <div class="space-y-4">
                    @if($comments->isNotEmpty())
                        @foreach($comments as $comment)
                            <div class="bg-gray-700 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold text-white">{{ $comment->user->name }}</h4>
                                        <p class="text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    @if(Auth::id() === $comment->user_id)
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-600">
                                                Supprimer
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <p class="mt-2 text-gray-300">{{ $comment->content }}</p>
                            </div>
                        @endforeach
                    @else
                        <p class="text-center text-gray-400">Aucun commentaire pour le moment. Soyez le premier à commenter !</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
