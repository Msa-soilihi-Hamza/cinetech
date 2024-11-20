<div class="comment-thread">
    <!-- Commentaire principal -->
    <div class="comment-main p-4 bg-gray-100 rounded-lg mb-4">
        <div class="flex justify-between items-start">
            <div>
                <h4 class="font-bold">{{ $comment->user->name }}</h4>
                <p class="text-sm text-gray-500">{{ $comment->created_at->diffForHumans() }}</p>
            </div>
            @if(Auth::id() === $comment->user_id)
                <div class="flex space-x-2">
                    <button onclick="toggleEditForm({{ $comment->id }})" class="text-blue-500">
                        Modifier
                    </button>
                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Supprimer</button>
                    </form>
                </div>
            @endif
        </div>
        <p class="mt-2">{{ $comment->content }}</p>
        
        <!-- Formulaire de réponse -->
        @auth
            <form action="{{ route('comments.reply', $comment) }}" method="POST" class="mt-4">
                @csrf
                <textarea 
                    name="content" 
                    rows="2" 
                    class="w-full p-2 border rounded-lg"
                    placeholder="Répondre à ce commentaire..."></textarea>
                <button type="submit" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded-lg">
                    Répondre
                </button>
            </form>
        @endauth
    </div>

    <!-- Réponses -->
    <div class="replies ml-8">
        @foreach($comment->replies as $reply)
            <div class="reply p-4 bg-gray-50 rounded-lg mb-2">
                <div class="flex justify-between items-start">
                    <div>
                        <h5 class="font-bold">{{ $reply->user->name }}</h5>
                        <p class="text-sm text-gray-500">{{ $reply->created_at->diffForHumans() }}</p>
                    </div>
                    @if(Auth::id() === $reply->user_id)
                        <div class="flex space-x-2">
                            <button onclick="toggleEditForm({{ $reply->id }})" class="text-blue-500">
                                Modifier
                            </button>
                            <form action="{{ route('comments.destroy', $reply) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500">Supprimer</button>
                            </form>
                        </div>
                    @endif
                </div>
                <p class="mt-2">{{ $reply->content }}</p>
            </div>
        @endforeach
    </div>
</div> 