<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'featuredPost' => Post::featured(limit: 1)->get(),
            'staffPosts' => Post::staffPosts(4)->get(),
            'leaderboard' => Post::topMemberPosts(5)->get(),
            'communityFeed' => Post::communityFeed()->get(),
        ]);
    }
}
