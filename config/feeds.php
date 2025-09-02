<?php

return [
    'read' => [
        'per_source' => 10,
        'interleave' => true,
        'ttl_minutes' => 60,
    ],
    'listen' => [
        'per_source'    => 6,
        'interleave'    => true,
        'strict_daily'  => true,
        'grace_minutes' => 5,
        'filters' => [
            'include_regex_by_label' => [
                'Metal Blade' => [
                    '/\bofficial\s+video\b/i',
                    '/\bofficial\b/i',
                    '/\blyric\s+video\b/i',
                    '/\bfull\s+album\b/i',
                    '/\bplaythrough\b/i', // guitar/bass/drum
                ],
            ],
            // blacklist global (aplica a todos)
            'exclude_regex_global' => [
                '/\B#\w+/i',         // hashtags (#shorts #wretched ...)
                '/\bshorts?\b/i',    // SHORTS, (SHORTS), etc.
                '/\bteaser\b/i',
                '/\btrailer\b/i',
                '/\bsnippet\b/i',
                '/\bclip\b/i',       // si te cuela “video clip” y NO lo quieres
            ],
        ]
    ],
    'limits' => [
        'min' => 1,
        'max' => 10,
    ],
];