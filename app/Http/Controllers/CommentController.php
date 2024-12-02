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
            'content' => [
                'required',
                'string',
                'min:2',
                'max:1000',
                'not_regex:/^[\s]*$/',
            ],
            'media_type' => [
                'required',
                'string',
                'in:movie,tv'
            ],
            'media_id' => [
                'required',
                'integer',
                'min:1'
            ],
            'parent_id' => [
                'nullable',
                'exists:comments,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $parentComment = Comment::find($value);
                        if ($parentComment && $parentComment->parent_id) {
                            $fail('Les réponses imbriquées ne sont pas autorisées.');
                        }
                    }
                }
            ]
        ]);

        $comment = Comment::create([
            'content' => strip_tags(trim($validated['content'])),
            'media_type' => strtolower($validated['media_type']),
            'media_id' => $validated['media_id'],
            'parent_id' => $validated['parent_id'] ?? null,
            'user_id' => Auth::id()
        ]);

        return back()->with('success', 'Commentaire ajouté avec succès');
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

    public function reply(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'content' => [
                'required',
                'string',
                'min:2',
                'max:1000',
                'not_regex:/^[\s]*$/',
            ]
        ]);

        if ($comment->parent_id) {
            return back()->with('error', 'Les réponses imbriquées ne sont pas autorisées.');
        }

        $reply = Comment::create([
            'content' => strip_tags(trim($validated['content'])),
            'user_id' => Auth::id(),
            'media_type' => $comment->media_type,
            'media_id' => $comment->media_id,
            'parent_id' => $comment->id
        ]);

        return back()->with('success', 'Réponse ajoutée avec succès');
    }
}
