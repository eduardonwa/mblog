<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        
        $posts = Post::with('category', 'user')
            ->withCount('likes', 'comments')
            ->where('category_id', $category->id)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->paginate(12);

        if (request()->wantsJson()) {
            return response()->json($posts);
        }

        return Inertia::render('categories/index', [
            'posts' => $posts,
            'category' => $category
        ]);
    }
}
