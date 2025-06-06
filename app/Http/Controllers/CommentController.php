<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\JsonResponse; // <-- Add this import
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    /**
     * Store a newly created comment in storage.
     */
    public function store(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            // Note: The frontend form should have a field named 'content'
            'content' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'parent_id' => $validated['parent_id'] ?? null,
        ]);

        // Check if the request is looking for a JSON response
        if ($request->wentsJson()) {
            // Eager load the 'user' relationship to include the commenter's name in the response
            $comment->load('user');
            // Return the new comment as JSON with a '201 Created' status
            return response()->json($comment, 201);
        }

        // Fallback for non-AJAX requests
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
            // Return a simple success message for the AJAX call
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

        // Standardized the validation key to 'content' to match the store method
        $validated = $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $comment->update($validated);

        if ($request->wantsJson()) {
            // Return the updated comment data
            $comment->load('user');
            return response()->json($comment);
        }

        return redirect(route('posts.index'));
    }
}