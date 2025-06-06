<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // <-- Add this import

class PostLikeController extends Controller
{
    /**
     * Toggles the like status of a post for the authenticated user.
     *
     * This single method replaces the separate like() and unlike() methods.
     * It handles both liking and unliking a post and returns a JSON
     * response suitable for AJAX calls.
     *
     * @param Request $request
     * @param Post $post
     * @return JsonResponse
     */
    public function toggle(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        // The toggle() method on a BelongsToMany relationship is perfect for this.
        // It checks if the relationship exists. If not, it attaches it (likes).
        // If it does exist, it detaches it (unlikes).
        $user->likes()->toggle($post->id);

        // We use fresh() to get the most up-to-date model from the database,
        // ensuring our like count is accurate after the toggle.
        $post->loadCount('likes');

        // After toggling, we check if the relationship still exists to determine the new status.
        $isLiked = $user->likes()->where('post_id', $post->id)->exists();

        // Return a JSON response with the new like count and the current like status.
        // Your JavaScript will use this to update the UI.
        return response()->json([
            'likes_count' => $post->likes_count,
            'liked' => $isLiked,
        ]);
    }
}