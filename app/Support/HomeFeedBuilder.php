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
        $readCfg    = config('feeds.read');
        $listenCfg  = config('feeds.listen');

        $min = config('feeds.limits.min', 1);
        $max = config('feeds.limits.max', 10);

        // Overrides por query
        $readCfg['per_source']   = max($min, min($max, (int) $request->query('read_per',   $readCfg['per_source'])));
        $listenCfg['per_source'] = max($min, min($max, (int) $request->query('listen_per', $listenCfg['per_source'])));
        
        // Clamp
        $readCfg['per_source']   = max($min, min($max, $readCfg['per_source']));
        $listenCfg['per_source'] = max($min, min($max, $listenCfg['per_source']));
        
        // READ (noticias)
        $readFeeds = FeedCatalogue::news();
        $read = $this->news->collect($readFeeds, $readCfg);

        // LISTEN (videos)
        $videoFeeds = array_filter(FeedCatalogue::youtube(), fn($u) => is_string($u) && $u !== '');
        $listen = $this->videos->collect($videoFeeds, $listenCfg);

        return [
            'readFeed'   => ['data' => $read, 'next_page_url' => null],
            'listenFeed' => ['data' => $listen, 'next_page_url' => null],
        ];
    }
}