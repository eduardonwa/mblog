<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            // 'staffPosts' => Post::staffPosts(limit: 10)->get(),
            'featuredPosts' => Post::with('media')->featuredPosts(limit: 6)->get(), // ultimos 3 del staff
            'leaderboard' => Post::mostLiked()->get()->filter(fn($post) => $post->likes_count > 0), // mas votados por la comunidad
            'recent' => Post::recent()->get(), // ultimos 4 de la comunidad
        ]);
    }
}
