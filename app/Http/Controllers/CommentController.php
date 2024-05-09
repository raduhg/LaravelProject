<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class CommentController extends Controller
{
    public function store(Post $post, Request $request)
    {

        $comment = new Comment();
        $comment->post_id= $post->id;
        $comment->user_id= Auth::id();
        $comment->content = request()->get('content');
        $comment->save();

        return redirect(route('posts.index', $post->id));
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        Gate::authorize('delete', $comment);
 
        $comment->delete();
 
        return redirect(route('posts.index'))->with('success', 'Comment deleted!');
    }

    public function edit(Comment $comment): View
    {
        Gate::authorize('update', $comment);
 
        return view('comment.edit', [
            'comment' => $comment,
        ])->with('success', 'comment edited successfully!');
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        
        Gate::authorize('update', $comment);
 
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);
 
        $comment->update($validated);
 
        return redirect(route('posts.index'));
    }

}
