<?php

namespace App\Support;

use Illuminate\Http\Request;
use App\Support\NewsFeed;
use App\Support\VideoFeed;

class HomeFeedBuilder
{
    public function __construct(
        protected NewsFeed $news,
        protected VideoFeed $videos,
    ) {}

    /** Devuelve los props ya listos para Inertia */
    public function build(Request $request): array
    {
        // READ (noticias)
        $readFeeds = FeedCatalogue::news();
        $read = $this->news->collect($readFeeds, [
            'per_source' => 10,
            'interleave' => true,
            'ttl_minutes' => 60,
        ]);

        // LISTEN (videos)
        $videoFeeds = array_filter(FeedCatalogue::youtube(), fn($u) => is_string($u) && $u !== '');
        $listen = $this->videos->collect($videoFeeds, [
            'per_source' => (int) $request->query('per_source', 15),
            'interleave' => $request->boolean('interleave', true),
            'strict_daily' => true,
            'grace_minutes' => 5,
        ]);

        return [
            'readFeed'   => ['data' => $read, 'next_page_url' => null],
            'listenFeed' => ['data' => $listen, 'next_page_url' => null],
        ];
    }
}