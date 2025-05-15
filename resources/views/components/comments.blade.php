@props(['mediaType', 'mediaId', 'comments'])

<div class="mt-12 bg-gray-800 rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-6 text-white">Commentaires</h2>
    
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
                        @if(Auth::id() === $comment->user_id || Auth::user()->isAdmin())
                            <div class="flex space-x-2">
                                <button 
                                    type="button"
                                    onclick="toggleEditForm('comment-{{ $comment->id }}')" 
                                    class="btn-edit px-2 py-1 text-blue-400 hover:text-blue-300 flex items-center">
                                    <i class="fas fa-edit mr-1"></i> Modifier
                                </button>
                                
                                <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete px-2 py-1 text-red-400 hover:text-red-300 flex items-center">
                                        <i class="fas fa-trash mr-1"></i> Supprimer
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
                            <button 
                                type="button" 
                                onclick="toggleEditForm('comment-{{ $comment->id }}')" 
                                class="px-3 py-1 bg-gray-500 text-white rounded-lg">
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
                        <button 
                            type="button"
                            onclick="toggleReplyForm('reply-form-{{ $comment->id }}')" 
                            class="btn-reply text-sm text-purple-400 hover:text-purple-300 flex items-center">
                            <i class="fas fa-reply mr-1"></i> Répondre
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
                    <div class="mt-4 ml-6 space-y-3 replies">
                        @foreach($comment->replies as $reply)
                            <div class="bg-gray-800 rounded-lg p-3" id="comment-{{ $reply->id }}">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h5 class="font-bold text-white">{{ $reply->user->name }}</h5>
                                        <p class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                    
                                    @auth
                                        @if(Auth::id() === $reply->user_id || Auth::user()->isAdmin())
                                            <div class="flex space-x-2">
                                                <button 
                                                    type="button"
                                                    onclick="toggleEditForm('comment-{{ $reply->id }}')" 
                                                    class="btn-edit px-2 py-1 text-blue-400 hover:text-blue-300 flex items-center">
                                                    <i class="fas fa-edit mr-1"></i> Modifier
                                                </button>
                                                
                                                <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-delete px-2 py-1 text-red-400 hover:text-red-300 flex items-center">
                                                        <i class="fas fa-trash mr-1"></i> Supprimer
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
                                            <button 
                                                type="button" 
                                                onclick="toggleEditForm('comment-{{ $reply->id }}')" 
                                                class="px-3 py-1 bg-gray-500 text-white rounded-lg">
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

<script>
// Fonction pour basculer l'affichage du formulaire d'édition
function toggleEditForm(id) {
    console.log('toggleEditForm appelé avec id:', id);
    
    const commentElement = document.getElementById(id);
    if (!commentElement) {
        console.error('Élément de commentaire non trouvé:', id);
        return;
    }
    
    const contentElement = commentElement.querySelector('.comment-content');
    const formElement = commentElement.querySelector('.comment-edit-form');
    
    console.log('Éléments trouvés:', {
        commentElement: !!commentElement,
        contentElement: !!contentElement,
        formElement: !!formElement
    });
    
    if (contentElement && formElement) {
        contentElement.classList.toggle('hidden');
        formElement.classList.toggle('hidden');
        console.log('Basculement effectué');
    } else {
        console.error('Éléments de contenu ou de formulaire non trouvés dans:', id);
    }
}

// Fonction pour basculer l'affichage du formulaire de réponse
function toggleReplyForm(id) {
    console.log('toggleReplyForm appelé avec id:', id);
    
    const replyForm = document.getElementById(id);
    if (replyForm) {
        replyForm.classList.toggle('hidden');
        console.log('Basculement du formulaire de réponse effectué');
    } else {
        console.error('Formulaire de réponse non trouvé:', id);
    }
}
</script> 