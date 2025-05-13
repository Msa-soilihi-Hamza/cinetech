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

            // Création du commentaire
            $comment = Comment::create([
                'user_id' => auth()->id(),
                'content' => $validated['content'],
                'commentable_type' => $validated['commentable_type'],
                'commentable_id' => $validated['commentable_id'],
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
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return back()->with('success', 'Commentaire supprimé avec succès');
    }

    public function index(Request $request)
    {
        $comments = Comment::where('media_type', $request->media_type)
            ->where('media_id', $request->media_id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('comments.index', [
            'comments' => $comments,
            'media_type' => $request->media_type,
            'media_id' => $request->media_id
        ]);
    }

    public function show(Comment $comment)
    {
        $comment->load(['user', 'replies.user']);

        return view('comments.show', [
            'comment' => $comment,
            'media_type' => $comment->media_type,
            'media_id' => $comment->media_id
        ]);
    }

    public function reply(Request $request, Comment $comment)
    {
        try {
            // Validation des données
            $validated = $request->validate([
                'content' => 'required|string|max:1000'
            ]);

            // Création de la réponse
            $reply = Comment::create([
                'user_id' => auth()->id(),
                'content' => $validated['content'],
                'commentable_type' => $comment->commentable_type,
                'commentable_id' => $comment->commentable_id,
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
                ->with('error', 'Une erreur est survenue lors de la création de la réponse.');
        }
    }
}
