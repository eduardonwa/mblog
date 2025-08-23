<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\NewsFeed;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class WelcomeController extends Controller
{
    protected $spotify;

    public function index(Request $request, NewsFeed $newsFeed)
    {   
        // crea filtros y obtener posts mejor rankeados
        $order = $request->get('order', 'hot');
        $query = Post::communityFeed();
        switch ($order) {
            case 'newest':
                $query->orderByDesc('published_at');
                break;
            case 'channel':
                $query->orderBy('channel_id');
                break;
            default: // hot
                $query->orderByDesc('crushing_score')->orderByDesc('published_at');
                break;
        }
        // obtener posts y regresar json
        $communityFeed = $query->paginate(12);
        $communityFeed->appends(['json' => 'true']);
        if (request()->wantsJson()) {
            return response()->json($communityFeed);
        }

        $feeds = [
            'Nuclear Blast' => 'https://www.nuclearblast.com/blogs/news.atom',
            // 'Season of Mist (News)' => 'https://www.season-of-mist.com/news/feed/',
        ];

        $items = $newsFeed->collect($feeds, [
            'per_source'  => 10,
            'interleave'  => true,
            'ttl_minutes' => 60,
        ]);

/*         $activeBlock = Cache::store('metal-scraper')->get('metal.new_releases.active_block', 0);
        $albums = Cache::store('metal-scraper')->get("metal.new_releases.block_{$activeBlock}", []); */
        
        return Inertia::render('Welcome', [
            'featuredPost' => Post::featured(limit: 1)->withCount('comments')->get(),
            'staffPosts' => Post::staffPosts(4)->get(),
            'leaderboard' => Post::topMemberPosts(5)->with('channel')->get(),
            'communityFeed' => $communityFeed,
            // 'albums' => $albums,
            'order' => $order,
            'newsFeed' => [
                'data' => $items,
                'next_page_url' => null
            ],
        ]);
    }
}
