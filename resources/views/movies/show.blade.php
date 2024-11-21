@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden">
            {{-- Image de fond --}}
            <div class="relative h-100">
                @if(isset($movie['backdrop_path']) && $movie['backdrop_path'])
                    <img src="https://image.tmdb.org/t/p/original{{ $movie['backdrop_path'] }}"
                         alt="{{ $movie['title'] ?? '' }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"></div>
                @endif
            </div>

            {{-- Contenu --}}
            <div class="relative -mt-32 px-6 md:px-12 pb-12">
                <div class="flex flex-col md:flex-row gap-8">
                    {{-- Affiche --}}
                    <div class="flex-shrink-0 w-full md:w-1/3">
                        @if(isset($movie['poster_path']))
                            <img src="https://image.tmdb.org/t/p/w500{{ $movie['poster_path'] }}"
                                 alt="{{ $movie['title'] }}"
                                 class="w-full rounded-lg shadow-xl">
                        @endif
                    </div>

                    {{-- Informations --}}
                    <div class="flex-grow text-white">
                        <h1 class="text-4xl font-bold mb-4">{{ $movie['title'] }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-4 mb-6">
                            <span class="text-purple-500 font-bold text-xl">
                                {{ number_format($movie['vote_average'], 1) }}/10
                            </span>
                            @if(isset($movie['release_date']))
                                <span class="text-gray-400">
                                    {{ \Carbon\Carbon::parse($movie['release_date'])->format('d/m/Y') }}
                                </span>
                            @endif
                            @if(isset($movie['genres']))
                                <div class="flex flex-wrap gap-2">
                                    @foreach($movie['genres'] as $genre)
                                        <span class="px-3 py-1 bg-gray-700 rounded-full text-sm">
                                            {{ $genre['name'] }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        @if(isset($movie['overview']))
                            <div class="mb-8">
                                <h2 class="text-xl font-semibold mb-2">Synopsis</h2>
                                <p class="text-gray-300 leading-relaxed">{{ $movie['overview'] }}</p>
                            </div>
                        @endif

                        @if(isset($movie['credits']['cast']))
                            <div class="cast-section">
                                <h2 class="text-xl font-semibold mb-4">Distribution principale</h2>
                                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                    @foreach(array_slice($movie['credits']['cast'], 0, 8) as $actor)
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
                        <input type="hidden" name="media_type" value="movie">
                        <input type="hidden" name="media_id" value="{{ $movie['id'] }}">
                        
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

                                @auth
                                    <button onclick="toggleReplyForm({{ $comment->id }})" 
                                            class="text-purple-500 hover:text-purple-600 text-sm mt-3 flex items-center gap-2 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                        </svg>
                                        Répondre
                                    </button>

                                    <div id="reply-form-{{ $comment->id }}" class="mt-3 hidden">
                                        <form action="{{ route('comments.reply', $comment) }}" method="POST" 
                                              class="bg-gray-750 p-4 rounded-lg border border-gray-600">
                                            @csrf
                                            <textarea 
                                                name="content" 
                                                rows="2" 
                                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:border-purple-500 focus:ring-1 focus:ring-purple-500"
                                                placeholder="Votre réponse..."
                                                required></textarea>
                                            <div class="mt-3 flex gap-2">
                                                <button type="submit" 
                                                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center gap-2">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                    </svg>
                                                    Envoyer
                                                </button>
                                                <button type="button" 
                                                        onclick="toggleReplyForm({{ $comment->id }})" 
                                                        class="px-4 py-2 text-gray-400 hover:text-white border border-gray-600 rounded-lg hover:bg-gray-700 transition-colors">
                                                    Annuler
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                @endauth

                                @if($comment->replies->count() > 0)
                                    <div class="ml-8 mt-4 space-y-3 border-l-2 border-purple-500 pl-4">
                                        @foreach($comment->replies as $reply)
                                            <div class="bg-gray-800 rounded-lg p-4 shadow-lg transform hover:scale-[1.02] transition-transform">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex items-center gap-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                                                        </svg>
                                                        
                                                        <div>
                                                            <h5 class="font-bold text-white flex items-center gap-2">
                                                                {{ $reply->user->name }}
                                                                <span class="text-xs text-purple-400 font-normal">Réponse</span>
                                                            </h5>
                                                            <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                                        </div>
                                                    </div>
                                                    
                                                    @if(Auth::id() === $reply->user_id)
                                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="text-red-500 hover:text-red-600 text-sm bg-gray-700 px-2 py-1 rounded-lg transition-colors">
                                                                Supprimer
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                
                                                <div class="mt-2 text-gray-300 bg-gray-750 p-3 rounded-lg">
                                                    {{ $reply->content }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
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

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById(`reply-form-${commentId}`);
    form.classList.toggle('hidden');
}
</script>
@endsection 