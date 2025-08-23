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
            'Nuclear Blast' => VideoFeed::ytFeed('UCoxg3Kml41wE3IPq-PC-LQw'),
        ];
    }
}