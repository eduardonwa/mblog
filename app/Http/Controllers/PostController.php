<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PostController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $post = Post::with('category', 'author', 'tags')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();
        
        $tags = $post->tags;

        // si el post no se encuentra redirigr a la raíz
        if (!$post) {
            return redirect('/');
        }

        return view('post.show', compact('post', 'tags'));
    }

    public function postByTag($slug)
    {
        $posts = Post::withAnyTags([$slug])
            ->where('status', 'published')
            ->where('created_at', '<', Carbon::now())
            ->orderBy('created_at', 'desc')
            ->with('tags:id,name,slug')
            ->paginate(8);

        return view('tags', compact('posts', 'slug'));
    }
}
