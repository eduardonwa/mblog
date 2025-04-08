<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $staffPosts = Post::with('category', 'author')
            ->where('status', 'published')
            ->staffPosts()
            ->latest()
            ->take(8)
            ->get();

        $leaderboard = Post::mostLiked(5)->get();

        return Inertia::render('Welcome', [
            'staffPosts' => $staffPosts,
            'leaderboard' => $leaderboard->filter(fn($post) => $post->likes_count > 0)
        ]);
    }
}
