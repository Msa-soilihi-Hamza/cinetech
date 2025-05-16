@extends('admin.layouts.app')

@section('title', 'Gestion des commentaires')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4">Gestion des commentaires</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
        <li class="breadcrumb-item active">Commentaires</li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-comments me-1"></i>
            Liste des commentaires
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form action="{{ route('admin.comments.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Rechercher</button>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Réinitialiser</a>
                    </div>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Utilisateur</th>
                            <th>Contenu</th>
                            <th>Type de média</th>
                            <th>Date de création</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($comments as $comment)
                            <tr>
                                <td>{{ $comment->id }}</td>
                                <td>{{ $comment->user->name ?? 'Utilisateur supprimé' }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($comment->content, 100) }}</td>
                                <td>
                                    @if($comment->commentable_type == 'App\Models\Movie')
                                        Film
                                    @elseif($comment->commentable_type == 'App\Models\TvShow')
                                        Série TV
                                    @else
                                        {{ $comment->commentable_type }}
                                    @endif
                                    (ID: {{ $comment->commentable_id }})
                                </td>
                                <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-info view-comment" data-bs-toggle="modal" data-bs-target="#viewCommentModal" data-comment-id="{{ $comment->id }}" data-comment-content="{{ $comment->content }}" data-comment-user="{{ $comment->user->name ?? 'Utilisateur supprimé' }}" data-comment-date="{{ $comment->created_at->format('d/m/Y H:i') }}">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="d-inline delete-comment-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Aucun commentaire trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher le commentaire complet -->
<div class="modal fade" id="viewCommentModal" tabindex="-1" aria-labelledby="viewCommentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewCommentModalLabel">Détails du commentaire</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Utilisateur:</strong> <span id="comment-user"></span></p>
                <p><strong>Date:</strong> <span id="comment-date"></span></p>
                <p><strong>Contenu:</strong></p>
                <div id="comment-content" class="p-3 bg-light rounded"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestion de la modal pour afficher le commentaire
        const viewCommentModal = document.getElementById('viewCommentModal');
        if (viewCommentModal) {
            viewCommentModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const content = button.getAttribute('data-comment-content');
                const user = button.getAttribute('data-comment-user');
                const date = button.getAttribute('data-comment-date');
                
                document.getElementById('comment-content').textContent = content;
                document.getElementById('comment-user').textContent = user;
                document.getElementById('comment-date').textContent = date;
            });
        }

        // Confirmation de suppression
        const deleteForms = document.querySelectorAll('.delete-comment-form');
        deleteForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                if (confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection 