<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostLikeController extends Controller
{
    public function like(Post $post)
    {
        $liked_by = auth()->user();
        $liked_by->likes()->attach($post);

        return redirect()->route('posts.index');
    }

    public function unlike(Post $post)
    {
        $liked_by = auth()->user();
        $liked_by->likes()->detach($post);

        return redirect()->route('posts.index');
    }
}
