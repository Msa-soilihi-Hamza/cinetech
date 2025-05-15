<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function store(Request $request)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'content' => 'required|string|max:1000',
                'commentable_type' => 'required|string',
                'commentable_id' => 'required|integer',
                'parent_id' => 'nullable|integer|exists:comments,id'
            ]);

            // Déterminer le media_type
            $mediaType = 'movie';
            if ($validated['commentable_type'] === 'App\Models\TvShow') {
                $mediaType = 'tv';
            }

            // Création du commentaire
            $comment = Comment::create([
                'user_id' => auth()->id(),
                'content' => $validated['content'],
                'commentable_type' => $validated['commentable_type'],
                'commentable_id' => $validated['commentable_id'],
                'media_type' => $mediaType,
                'media_id' => $validated['commentable_id'],
                'parent_id' => $validated['parent_id'] ?? null
            ]);

            // Redirection vers la bonne page
            if ($validated['commentable_type'] === 'App\Models\Movie') {
                return redirect()->route('movies.show', $validated['commentable_id'])
                    ->with('success', 'Votre commentaire a été ajouté avec succès.');
            } else {
                return redirect()->route('tv.show', $validated['commentable_id'])
                    ->with('success', 'Votre commentaire a été ajouté avec succès.');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du commentaire: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création du commentaire.');
        }
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        try {
            // Vérifier l'autorisation
            $this->authorize('update', $comment);

            $validated = $request->validate([
                'content' => [
                    'required',
                    'string',
                    'min:2',
                    'max:1000',
                    'not_regex:/^[\s]*$/',
                ]
            ]);

            $comment->update([
                'content' => strip_tags(trim($validated['content']))
            ]);

            return back()->with('success', 'Commentaire modifié avec succès');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::error('Erreur d\'autorisation lors de la modification du commentaire: ' . $e->getMessage());
            return back()->with('error', 'Vous n\'êtes pas autorisé à modifier ce commentaire.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification du commentaire: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la modification du commentaire.');
        }
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        try {
            // Vérifier l'autorisation
            $this->authorize('delete', $comment);

            $comment->delete();

            return back()->with('success', 'Commentaire supprimé avec succès');
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            Log::error('Erreur d\'autorisation lors de la suppression du commentaire: ' . $e->getMessage());
            return back()->with('error', 'Vous n\'êtes pas autorisé à supprimer ce commentaire.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du commentaire: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression du commentaire.');
        }
    }

    public function index(Request $request)
    {
        $mediaType = $request->media_type;
        $mediaId = $request->media_id;
        
        $commentableType = $mediaType === 'movie' ? 'App\Models\Movie' : 'App\Models\TvShow';
        
        $comments = Comment::where('commentable_type', $commentableType)
            ->where('commentable_id', $mediaId)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('comments.index', [
            'comments' => $comments,
            'mediaType' => $mediaType,
            'mediaId' => $mediaId
        ]);
    }

    public function show(Comment $comment)
    {
        $comment->load(['user', 'replies.user']);

        return view('comments.show', [
            'comment' => $comment,
            'mediaType' => $comment->commentable_type === 'App\Models\Movie' ? 'movie' : 'tv',
            'mediaId' => $comment->commentable_id
        ]);
    }

    public function reply(Request $request, Comment $comment)
    {
        try {
            // Vérifier si l'utilisateur est authentifié
            if (!auth()->check()) {
                return redirect()->route('login')
                    ->with('error', 'Vous devez être connecté pour répondre à un commentaire.');
            }

            // Validation des données
            $validated = $request->validate([
                'content' => 'required|string|max:1000'
            ]);

            // Déterminer le media_type
            $mediaType = 'movie';
            if ($comment->commentable_type === 'App\Models\TvShow') {
                $mediaType = 'tv';
            }

            // Création de la réponse
            $reply = Comment::create([
                'user_id' => auth()->id(),
                'content' => strip_tags(trim($validated['content'])),
                'commentable_type' => $comment->commentable_type,
                'commentable_id' => $comment->commentable_id,
                'media_type' => $mediaType,
                'media_id' => $comment->commentable_id,
                'parent_id' => $comment->id
            ]);

            // Redirection vers la bonne page
            if ($comment->commentable_type === 'App\Models\Movie') {
                return redirect()->route('movies.show', $comment->commentable_id)
                    ->with('success', 'Votre réponse a été ajoutée avec succès.');
            } else {
                return redirect()->route('tv.show', $comment->commentable_id)
                    ->with('success', 'Votre réponse a été ajoutée avec succès.');
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création de la réponse: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la création de la réponse: ' . $e->getMessage());
        }
    }
}
