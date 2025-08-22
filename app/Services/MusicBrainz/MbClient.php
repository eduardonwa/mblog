<?php

namespace App\Services\MusicBrainz;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class MbClient
{
    protected string $base = 'https://musicbrainz.org/ws/2';

    protected function http()
    {
        return Http::retry(3, 800)->withHeaders([
            'User-Agent' => 'sickofmetal/1.0 (+https://sickofmetal.net; admin@sickofmetal.net)',
            'Accept'     => 'application/json',
        ]);
    }

    /** Devuelve MBID del label más probable por nombre. */
    public function findLabelMbid(string $name): ?string
    {
        $res = $this->http()->get($this->base.'/label', [
            'query' => $name,   // también vale: 'query' => 'label:"Century Media"'
            'fmt'   => 'json',
            'limit' => 5,
        ]);
        if (!$res->ok()) return null;

        $labels = $res->json('labels') ?? [];
        // elegir el de mejor score y nombre más cercano
        usort($labels, fn($a,$b) => ($b['score'] ?? 0) <=> ($a['score'] ?? 0));
        foreach ($labels as $l) {
            $lname = Str::lower($l['name'] ?? '');
            if (Str::contains($lname, 'century media')) {
                return $l['id'] ?? null; // MBID
            }
        }
        // Fallback al primero
        return $labels[0]['id'] ?? null;
    }
}
