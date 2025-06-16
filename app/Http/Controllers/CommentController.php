<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Notifications\NewCommentReply;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
   public function store(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        if ($comment->parent_id) {
            $parentComment = Comment::find($comment->parent_id);
            // No notification if a user replies to their own comment
            if ($parentComment && $parentComment->user_id !== $comment->user_id) {
                $parentComment->user->notify(new NewCommentReply($comment));
            }
        }

        if ($request->wantsJson()) {

            $comment->load('user'); 
            
            return response()->json($comment, 201);
        }

        return redirect()->route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('delete', $comment);

        $comment->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Comment deleted successfully!']);
        }

        return redirect(route('posts.index'))->with('success', 'Comment deleted!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment): View
    {
        Gate::authorize('update', $comment);

        return view('comment.edit', [
            'comment' => $comment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment): RedirectResponse|JsonResponse
    {
        Gate::authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment->update($validated);

        if ($request->wantsJson()) {
            $comment->load('user');
            return response()->json($comment);
        }

        return redirect(route('posts.index'));
    }
}