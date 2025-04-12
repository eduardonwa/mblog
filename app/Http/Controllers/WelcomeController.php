<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'staffPosts' => Post::staffPosts(limit: 10)->get(), // últimos 10 posts del staff
            'featuredPosts' => Post::with('media')->featuredPosts(limit: 3)->get(), // ultimos 3 del staff
            'leaderboard' => Post::mostLiked()->get()->filter(fn($post) => $post->likes_count > 0), // mas votados de la comunidad
            'recent' => Post::recent()->get(), // ultimos 10 de la comunidad
        ]);
    }
}
