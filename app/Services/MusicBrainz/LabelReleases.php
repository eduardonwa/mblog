<?php

namespace App\Services\MusicBrainz;

use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class LabelReleases
{
    protected string $base = 'https://musicbrainz.org/ws/2';

    public function __construct(protected MbClient $client) {}

    protected function http()
    {
        return Http::retry(3, 800)->withHeaders([
            'User-Agent' => 'sickofmetal/1.0 (+https://sickofmetal.net; admin@sickofmetal.net)',
            'Accept'     => 'application/json',
        ]);
    }

    public function latestAlbumsByLabelFromToday(string $labelMbid, int $take = 20): array
    {
        $limit = 100;

        // 1) total para saltar al final
        $probe = $this->http()->get($this->base.'/release', [
            'label'  => $labelMbid,
            'inc'    => 'artist-credits+labels+release-groups',
            'fmt'    => 'json',
            'limit'  => 1,
            'offset' => 0,
        ]);
        if (!$probe->ok()) return [];
        $total = (int)($probe->json('release-count') ?? 0);
        if ($total === 0) return [];

        $offset    = max(0, $total - $limit);
        $today     = \Carbon\Carbon::today()->toDateString();
        $byRgid    = [];   // rgid => mejor edición elegida
        $pages     = 0;
        $maxPages  = 8;    // seguridad

        while (count($byRgid) < $take && $offset >= 0 && $pages < $maxPages) {
            $res = $this->http()->get($this->base.'/release', [
                'label'  => $labelMbid,
                'inc'    => 'artist-credits+labels+release-groups',
                'fmt'    => 'json',
                'limit'  => $limit,
                'offset' => $offset,
            ]);
            if (!$res->ok()) break;

            foreach (($res->json('releases') ?? []) as $r) {
                $rg      = $r['release-group'] ?? null;
                $primary = $rg['primary-type'] ?? null;    // Album | Single | EP | ...
                if ($primary !== 'Album') continue;        // ← solo Álbumes

                $band  = $r['artist-credit'][0]['name'] ?? null;
                $title = $r['title'] ?? null;
                $date  = $this->normalizeDate($r['date'] ?? null);
                if (!$band || !$title || !$date) continue;
                if ($date > $today) continue;              // descarta futuros

                $rgid = $rg['id'] ?? null;
                if (!$rgid) continue;

                $cand = [
                    'band'    => (string)$band,
                    'title'   => (string)$title,
                    'date'    => $date,
                    'type'    => $primary,
                    'country' => $r['country'] ?? null,
                    'rgid'    => $rgid,
                    'rid'     => $r['id'] ?? null,
                ];

                // dedupe por release-group → elige mejor edición
                if (!isset($byRgid[$rgid])) {
                    $byRgid[$rgid] = $cand;
                } else {
                    $byRgid[$rgid] = $this->preferEdition($byRgid[$rgid], $cand);
                }

                if (count($byRgid) >= $take) break;
            }

            $pages++;
            $offset -= $limit;
            usleep(1100000); // respeta rate limit
        }

        // Orden final por fecha desc y corta a N
        $out = array_values($byRgid);
        usort($out, fn($a,$b) => strcmp($b['date'], $a['date']));
        return array_slice($out, 0, $take);
    }

    /** Prefiere ediciones 'XW' (worldwide); si no, la fecha más reciente. */
    protected function preferEdition(array $cur, array $cand): array
    {
        $cx = $cur['country'] ?? null;
        $nx = $cand['country'] ?? null;

        if ($nx === 'XW' && $cx !== 'XW') return $cand;
        if ($cx === 'XW' && $nx !== 'XW') return $cur;

        // si ninguna es XW, elige la más reciente por fecha
        return strcmp($cand['date'], $cur['date']) >= 0 ? $cand : $cur;
    }

    protected function normalizeDate(?string $raw): ?string
    {
        if (!$raw) return null;
        try {
            if (preg_match('/^\d{4}$/', $raw))        $raw .= '-01-01';
            elseif (preg_match('/^\d{4}-\d{2}$/', $raw)) $raw .= '-01';
            return \Carbon\Carbon::parse($raw)->toDateString();
        } catch (\Throwable) { return null; }
    }
}
