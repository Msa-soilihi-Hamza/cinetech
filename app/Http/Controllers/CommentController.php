<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'media_type' => 'required|in:movie,tv',
            'media_id' => 'required|integer',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            ...$validated,
            'user_id' => Auth::id()
        ]);

        return back();
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update($validated);

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
            ->whereNull('parent_id') // Récupère uniquement les commentaires parents
            ->with(['user', 'replies.user']) // Charge les relations utilisateur et réponses
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
        // Charge le commentaire avec ses réponses et les utilisateurs associés
        $comment->load(['user', 'replies.user']);

        return view('comments.show', [
            'comment' => $comment,
            'media_type' => $comment->media_type,
            'media_id' => $comment->media_id
        ]);
    }

    public function reply(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $reply = Comment::create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
            'media_type' => $comment->media_type,
            'media_id' => $comment->media_id,
            'parent_id' => $comment->id
        ]);

        return back();
    }
}
