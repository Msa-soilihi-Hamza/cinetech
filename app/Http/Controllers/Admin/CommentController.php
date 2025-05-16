<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    /**
     * Display a listing of the comments.
     */
    public function index(Request $request)
    {
        $query = Comment::query()
            ->with(['user', 'commentable'])
            ->latest();

        // Filtrer par statut
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Rechercher
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($user) use ($search) {
                        $user->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $comments = $query->paginate(15);

        return view('admin.comments.index', compact('comments'));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected'
        ]);

        DB::beginTransaction();
        try {
            $comment->update($validated);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        DB::beginTransaction();
        try {
            $comment->delete();
            DB::commit();
            
            // Si c'est une requête AJAX, retourner une réponse JSON
            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }
            
            // Sinon, rediriger avec un message de succès
            return redirect()->route('admin.comments.index')
                ->with('success', 'Le commentaire a été supprimé avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Si c'est une requête AJAX, retourner une réponse JSON avec erreur
            if (request()->ajax()) {
                return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
            }
            
            // Sinon, rediriger avec un message d'erreur
            return redirect()->route('admin.comments.index')
                ->with('error', 'Une erreur est survenue lors de la suppression du commentaire: ' . $e->getMessage());
        }
    }
}
