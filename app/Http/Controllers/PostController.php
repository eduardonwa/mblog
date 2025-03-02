<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return view('post.show', compact('post'));
    }
}
