<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    protected $spotify;

    public function index()
    {   
        $communityFeed = Post::communityFeed()
            ->with([
                'user' => function($q) {
                $q->withTrashed();
            },
                'channel',
            ])
            ->withCount('comments')
            ->paginate(12);
            
        $communityFeed->appends(['json' => 'true']);

        if (request()->wantsJson()) {
            return response()->json($communityFeed);
        }

        $activeBlock = Cache::store('metal-scraper')->get('metal.new_releases.active_block', 0);
        $albums = Cache::store('metal-scraper')->get("metal.new_releases.block_{$activeBlock}", []);
        
        return Inertia::render('Welcome', [
            'featuredPost' => Post::featured(limit: 1)->withCount('comments')->get(),
            'staffPosts' => Post::staffPosts(4)->get(),
            'leaderboard' => Post::topMemberPosts(5)->get(),
            'communityFeed' => $communityFeed,
            'albums' => $albums,
        ]);
    }
}
