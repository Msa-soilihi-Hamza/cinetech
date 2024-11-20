<div class="comments-wrapper">
    <h2 class="text-2xl font-bold mb-4">Commentaires</h2>
    
    {{-- Formulaire d'ajout de commentaire --}}
    @auth
        <form action="{{ route('comments.store') }}" method="POST" class="mb-6">
            @csrf
            <input type="hidden" name="media_type" value="{{ $mediaType }}">
            <input type="hidden" name="media_id" value="{{ $mediaId }}">
            
            <div class="mb-4">
                <textarea 
                    name="content" 
                    rows="3" 
                    class="w-full px-3 py-2 border rounded-lg"
                    placeholder="Ajouter un commentaire..."></textarea>
            </div>
            
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">
                Publier
            </button>
        </form>
    @endauth

    {{-- Liste des commentaires --}}
    @if($comments->count() > 0)
        @foreach($comments as $comment)
            <div class="comment-container p-4 border rounded-lg mb-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-bold">{{ $comment->user->name }}</h4>
                        <p class="text-sm text-gray-500">
                            {{ $comment->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <p class="mt-2">{{ $comment->content }}</p>
            </div>
        @endforeach
    @else
        <p>Aucun commentaire pour le moment.</p>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle des formulaires de réponse
    document.querySelectorAll('.reply-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const form = this.nextElementSibling;
            form.classList.toggle('hidden');
        });
    });
});
</script>
@endpush 