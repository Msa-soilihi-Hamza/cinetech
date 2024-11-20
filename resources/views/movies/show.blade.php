@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

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

    {{-- Débogage des variables --}}
    @if(isset($comments))
        <!-- Ajoutez ceci temporairement pour déboguer -->
        <div class="text-white">
            {{ var_dump($comments) }}
        </div>
    @else
        <div class="text-white">
            Les commentaires ne sont pas définis
        </div>
    @endif

    {{-- Détails du film --}}
    <div class="movie-details mb-8">
        @if(isset($movie))
            <h1 class="text-2xl font-bold text-white">{{ $movie['title'] }}</h1>
        @else
            <div class="text-white">
                Le film n'est pas défini
            </div>
        @endif
    </div>

    {{-- Section commentaires --}}
    <div class="comments-wrapper mt-8">
        <h2 class="text-2xl font-bold mb-4 text-white">Commentaires</h2>
        
        {{-- Debug de l'authentification --}}
        @if(Auth::check())
            <p class="text-white">Utilisateur connecté: {{ Auth::user()->name }}</p>
        @else
            <p class="text-white">Utilisateur non connecté</p>
        @endif
        
        {{-- Formulaire d'ajout de commentaire --}}
        @auth
            <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
                @csrf
                <input type="hidden" name="media_type" value="movie">
                <input type="hidden" name="media_id" value="{{ $movie['id'] }}">
                
                <div class="mb-4">
                    <textarea 
                        name="content" 
                        rows="3" 
                        class="w-full px-3 py-2 border rounded-lg bg-gray-700 text-white"
                        placeholder="Ajouter un commentaire..."></textarea>
                </div>
                
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                    Publier
                </button>
            </form>
        @else
            <p class="text-gray-400 mb-4">
                <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Connectez-vous</a> 
                pour laisser un commentaire.
            </p>
        @endauth

        {{-- Liste des commentaires avec débogage --}}
        <div class="space-y-4">
            @if($comments->isEmpty())
                <p class="text-gray-400">Aucun commentaire pour le moment.</p>
            @else
                <p class="text-white">Nombre de commentaires : {{ $comments->count() }}</p>
                @foreach($comments as $comment)
                    <div class="comment-container bg-gray-800 p-4 rounded-lg">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-bold text-white">{{ $comment->user->name }}</h4>
                                <p class="text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            
                            @if(Auth::id() === $comment->user_id)
                                <div class="flex space-x-2">
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-600">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                        
                        <p class="mt-2 text-gray-300">{{ $comment->content }}</p>

                        {{-- Section réponses --}}
                        <div class="mt-4 space-y-2">
                            @foreach($comment->replies as $reply)
                                <div class="ml-8 bg-gray-700 p-3 rounded">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h5 class="font-bold text-white">{{ $reply->user->name }}</h5>
                                            <p class="text-sm text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                        </div>
                                        
                                        @if(Auth::id() === $reply->user_id)
                                            <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-600">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-gray-300">{{ $reply->content }}</p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Formulaire de réponse --}}
                        @auth
                            <div class="mt-4">
                                <form action="{{ route('comments.reply', $comment) }}" method="POST">
                                    @csrf
                                    <div class="flex gap-2">
                                        <input 
                                            type="text" 
                                            name="content" 
                                            class="flex-1 px-3 py-1 bg-gray-700 border border-gray-600 rounded-lg text-white"
                                            placeholder="Répondre à ce commentaire...">
                                        <button type="submit" class="px-4 py-1 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                                            Répondre
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @endauth
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection 