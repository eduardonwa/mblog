<?php

namespace App\Support;

use App\Support\VideoFeed;

class FeedCatalogue
{
    /** Feeds de noticias (RSS/Atom) */
    public static function news(): array
    {
        return [
            'Nuclear Blast' => 'https://www.nuclearblast.com/blogs/news.atom',
        ];
    }

    /** Feeds de YouTube (RSS por canal) */
    public static function youtube(): array
    {
        return [
            'Relapse' => VideoFeed::ytFeed('UC_E54fzF2BPsy7Ig4aDygWA'),
            'Black Metal Promotion' => VideoFeed::ytFeed('UCzCWehBejA23yEz3zp7jlcg'),
            'Rob Doom Hammer' => VideoFeed::ytFeed('UCwfonT2RaN3ovsu1Qa22Xbw'),
        ];
    }
}