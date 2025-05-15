@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gray-800 rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-white mb-6">Commentaires</h1>
            
            <div class="mb-6">
                <a href="{{ $mediaType === 'movie' ? route('movies.show', $mediaId) : route('tv.show', $mediaId) }}" 
                   class="inline-flex items-center text-purple-400 hover:text-purple-300">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Retour au {{ $mediaType === 'movie' ? 'film' : 'série' }}
                </a>
            </div>
            
            @auth
                <form action="{{ route('comments.store') }}" method="POST" class="mb-8">
                    @csrf
                    <input type="hidden" name="commentable_type" value="{{ $mediaType === 'movie' ? 'App\\Models\\Movie' : 'App\\Models\\TvShow' }}">
                    <input type="hidden" name="commentable_id" value="{{ $mediaId }}">
                    
                    <div class="mb-4">
                        <textarea 
                            name="content" 
                            rows="3" 
                            class="w-full p-3 bg-gray-700 text-white border border-gray-600 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500"
                            placeholder="Partagez votre avis..."></textarea>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Publier
                        </button>
                    </div>
                </form>
            @else
                <div class="bg-gray-700 rounded-lg p-4 mb-8 text-center">
                    <p class="text-gray-300">
                        <a href="{{ route('login') }}" class="text-purple-400 hover:text-purple-300">Connectez-vous</a> 
                        pour laisser un commentaire.
                    </p>
                </div>
            @endauth
            
            <div class="space-y-6">
                @forelse($comments as $comment)
                    <div class="bg-gray-700 rounded-lg p-4" id="comment-{{ $comment->id }}">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-bold text-white">{{ $comment->user->name }}</h4>
                                <p class="text-sm text-gray-400">{{ $comment->created_at->diffForHumans() }}</p>
                            </div>
                            
                            @auth
                                @if(Auth::id() === $comment->user_id)
                                    <div class="flex space-x-2">
                                        <button onclick="toggleEditForm('comment-{{ $comment->id }}')" class="text-blue-400 hover:text-blue-300">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-300">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            @endauth
                        </div>
                        
                        <div class="comment-content">
                            <p class="text-gray-300">{{ $comment->content }}</p>
                        </div>
                        
                        <div class="comment-edit-form hidden mt-2">
                            <form action="{{ route('comments.update', $comment) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <textarea 
                                    name="content" 
                                    rows="2" 
                                    class="w-full p-2 bg-gray-600 text-white border border-gray-500 rounded-lg">{{ $comment->content }}</textarea>
                                <div class="flex justify-end mt-2 space-x-2">
                                    <button type="button" onclick="toggleEditForm('comment-{{ $comment->id }}')" class="px-3 py-1 bg-gray-500 text-white rounded-lg">
                                        Annuler
                                    </button>
                                    <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-lg">
                                        Enregistrer
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        @auth
                            <div class="mt-3">
                                <button onclick="toggleReplyForm('reply-form-{{ $comment->id }}')" class="text-sm text-purple-400 hover:text-purple-300">
                                    Répondre
                                </button>
                                
                                <div id="reply-form-{{ $comment->id }}" class="reply-form hidden mt-2">
                                    <form action="{{ route('comments.reply', $comment) }}" method="POST">
                                        @csrf
                                        <textarea 
                                            name="content" 
                                            rows="2" 
                                            class="w-full p-2 bg-gray-600 text-white border border-gray-500 rounded-lg"
                                            placeholder="Votre réponse..."></textarea>
                                        <div class="flex justify-end mt-2">
                                            <button type="submit" class="px-3 py-1 bg-purple-600 text-white rounded-lg">
                                                Répondre
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endauth
                        
                        <!-- Réponses -->
                        @if($comment->replies->count() > 0)
                            <div class="mt-4 ml-6 space-y-3">
                                @foreach($comment->replies as $reply)
                                    <div class="bg-gray-800 rounded-lg p-3" id="comment-{{ $reply->id }}">
                                        <div class="flex justify-between items-start mb-2">
                                            <div>
                                                <h5 class="font-bold text-white">{{ $reply->user->name }}</h5>
                                                <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                            </div>
                                            
                                            @auth
                                                @if(Auth::id() === $reply->user_id)
                                                    <div class="flex space-x-2">
                                                        <button onclick="toggleEditForm('comment-{{ $reply->id }}')" class="text-blue-400 hover:text-blue-300">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        
                                                        <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="text-red-400 hover:text-red-300">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endif
                                            @endauth
                                        </div>
                                        
                                        <div class="comment-content">
                                            <p class="text-gray-300">{{ $reply->content }}</p>
                                        </div>
                                        
                                        <div class="comment-edit-form hidden mt-2">
                                            <form action="{{ route('comments.update', $reply) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <textarea 
                                                    name="content" 
                                                    rows="2" 
                                                    class="w-full p-2 bg-gray-600 text-white border border-gray-500 rounded-lg">{{ $reply->content }}</textarea>
                                                <div class="flex justify-end mt-2 space-x-2">
                                                    <button type="button" onclick="toggleEditForm('comment-{{ $reply->id }}')" class="px-3 py-1 bg-gray-500 text-white rounded-lg">
                                                        Annuler
                                                    </button>
                                                    <button type="submit" class="px-3 py-1 bg-blue-500 text-white rounded-lg">
                                                        Enregistrer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-gray-700 rounded-lg p-4 text-center">
                        <p class="text-gray-400">Aucun commentaire pour le moment. Soyez le premier à partager votre avis !</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    function toggleEditForm(id) {
        const commentElement = document.getElementById(id);
        const contentElement = commentElement.querySelector('.comment-content');
        const formElement = commentElement.querySelector('.comment-edit-form');
        
        contentElement.classList.toggle('hidden');
        formElement.classList.toggle('hidden');
    }
    
    function toggleReplyForm(id) {
        const replyForm = document.getElementById(id);
        replyForm.classList.toggle('hidden');
    }
</script>
@endsection 