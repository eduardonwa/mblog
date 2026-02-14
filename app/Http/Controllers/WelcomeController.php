<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Support\HomeFeedBuilder;
use Inertia\Inertia;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    public function index(Request $request, HomeFeedBuilder $homeFeeds)
    {   
        // crea filtros y obtener posts mejor rankeados
        $order = $request->get('order');
        $orderForQuery = $ordr ?? 'hot'; // default solo para ordenar en backend
        $query = Post::communityFeed();

        switch ($orderForQuery) {
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
        
        return Inertia::render('Welcome', [
            'featuredPost'  => Post::featured(limit: 1)->withCount('comments')->get(),
            'staffPosts'    => Post::staffPosts(4)->get(),
            'leaderboard'   => Post::topMemberPosts(5)->with('channel')->get(),
            'communityFeed' => $communityFeed,
            'order'         => $order,
            ...$homeFeeds->build($request),
        ]);
    }
}
