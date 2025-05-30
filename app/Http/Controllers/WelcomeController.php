<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Models\Category;

class WelcomeController extends Controller
{
    public function index()
    {   
        $communityFeed = Post::communityFeed()->withCount('comments')->paginate(12);
        $communityFeed->appends(['json' => 'true']);

        if (request()->wantsJson()) {
            return response()->json($communityFeed);
        }

        return Inertia::render('Welcome', [
            'featuredPost' => Post::featured(limit: 1)->withCount('comments')->get(),
            'staffPosts' => Post::staffPosts(4)->get(),
            'leaderboard' => Post::topMemberPosts(5)->get(),
            'communityFeed' => $communityFeed,
        ]);
    }
}
