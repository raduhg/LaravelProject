<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('posts.index', [
            // Eager load relationships for efficiency
            'posts' => Post::with('user', 'likes', 'comments.user')->latest()->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Typically not needed for a single-page style feed
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        $post = $request->user()->posts()->create($validated);

        // Check if the request is AJAX
        if ($request->wantsJson()) {
            // Eager load the 'user' relationship so it's included in the JSON response
            $post->load('user');
            // Return the new post as JSON with a '201 Created' status
            return response()->json($post, 201);
        }

        return redirect(route('posts.index'))->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', [
            'post' => $post,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post): RedirectResponse|JsonResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Consider deleting the old image if you replace it
            $imagePath = $request->file('image')->store('images', 'public');
            $validated['image'] = $imagePath;
        }

        $post->update($validated);

        if ($request->wantsJson()) {
            // Return the updated post data as JSON
            return response()->json($post);
        }

        return redirect(route('posts.index'))->with('success', 'Post updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Request $request): RedirectResponse|JsonResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        if ($request->wantsJson()) {
            // Return a success message with a '200 OK' or '204 No Content' status
            return response()->json(['message' => 'Post deleted successfully!']);
        }

        return redirect(route('posts.index'))->with('success', 'Post deleted!');
    }
}
