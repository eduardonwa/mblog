<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use App\Services\SpotifyConnect;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    protected $spotify;

    public function index()
    {   
        $communityFeed = Post::communityFeed()
            ->with(['user' => function($q) {
                $q->withTrashed();
            }])
            ->withCount('comments')
            ->paginate(12);
            
        $communityFeed->appends(['json' => 'true']);

        if (request()->wantsJson()) {
            return response()->json($communityFeed);
        }

        $albums = Cache::get('metal.new_releases', []);
        
        return Inertia::render('Welcome', [
            'featuredPost' => Post::featured(limit: 1)->withCount('comments')->get(),
            'staffPosts' => Post::staffPosts(4)->get(),
            'leaderboard' => Post::topMemberPosts(5)->get(),
            'communityFeed' => $communityFeed,
            'albums' => $albums,
        ]);
    }
}
