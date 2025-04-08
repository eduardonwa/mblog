<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index()
    {
        $staffPosts = Post::staffPosts()->get();
        $leaderboard = Post::mostLiked()->get();
        $recent = Post::recent()->get();

        return Inertia::render('Welcome', [
            'staffPosts' => $staffPosts,
            'leaderboard' => $leaderboard->filter(fn($post) => $post->likes_count > 0),
            'recent'=> $recent,
        ]);
    }
}
