<?php

return [
    'read' => [
        'per_source' => 10,
        'interleave' => true,
        'ttl_minutes' => 60,
    ],
    'listen' => [
        'per_source'    => 5,
        'interleave'    => true,
        'strict_daily'  => true,
        'grace_minutes' => 5,
    ],
    'limits' => [
        'min' => 1,
        'max' => 10,
    ],
];