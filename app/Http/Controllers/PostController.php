<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    public function show(Request $request, string $slug)
    {
        $post = Post::with([
            'category',
            'author',
            'tags:id,name,slug',
            'likes'
        ])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();
    
        $post->is_liked_by_user = Auth::check() 
            ? $post->likes()->where('user_id', Auth::id())->exists()
            : false;

        $post->thumbnail_url = $post->getFirstMediaUrl('thumbnails', 'lg_thumb');
    
        return Inertia::render('post/show', [
            'post' => $post,
            'meta' => [
                'title' => $post->meta_title ?? $post->title,
                'description' => $post->meta_description ?? $post->description,
                'author' => $post->author?->name,
            ],
        ]);
    }

    public function index()
    {
        $posts = Post::with('category', 'author')
            ->where('status', 'published')
            ->paginate(20);

        return Inertia::render('post/index', [
            'posts' => $posts,
        ]);
    }

    public function postByTag($slug)
    {
        $posts = Post::withAnyTags([$slug])
            ->with('tags:id,name,slug')
            ->where('status', 'published')
            ->paginate(8);
    
        return view('tags', compact('posts', 'slug'));
    }

    public function postByCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return view('categories', compact('posts', 'category'));
    }
}
