<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $post = Post::with('category', 'author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        // si el post no se encuentra redirigr a la raíz
        if (!$post) {
            return redirect('/');
        }
        return view('post.show', compact('post'));
    }
}
