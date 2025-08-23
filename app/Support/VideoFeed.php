<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Carbon;

class VideoFeed
{
    /**
     * @param array<string,string> $feeds  ["Label" => "https://www.youtube.com/feeds/videos.xml?channel_id=UC..."]
     * @param array{
     *   per_source?:int,
     *   interleave?:bool,
     *   strict_daily?:bool,
     *   grace_minutes?:int
     * } $opts
     * @return array<int,array{
     *   id:string,
     *   sourceLabel:string,
     *   title:string,
     *   url:string,
     *   thumb:?string,
     *   date:?string
     * }>
     */
    public function collect(array $feeds, array $opts = []): array
    {
        $perSource    = $opts['per_source']     ?? 15;   // YT ~15 ítems
        $interleave   = $opts['interleave']     ?? true;
        $strictDaily  = $opts['strict_daily']   ?? true; // después de la gracia, ¿mostrar stale?
        $graceMinutes = $opts['grace_minutes']  ?? 5;

        $bySource = [];

        foreach ($feeds as $label => $url) {
            $xml = $this->fetchDaily($url, $strictDaily, $graceMinutes);
            if ($xml === null) { $bySource[$label] = []; continue; }

            $items = $this->parse($xml); // title, url, thumb, date

            // Precompute ts para ordenar por fecha (robusto con Carbon)
            $items = array_map(function ($it) {
                $it['ts'] = 0;
                if (!empty($it['date'])) {
                    try { $it['ts'] = Carbon::parse($it['date'])->timestamp; } catch (\Throwable) {}
                }
                return $it;
            }, $items);

            // Ordenar por ts desc y cortar
            usort($items, fn($a,$b) => $b['ts'] <=> $a['ts']);
            $items = array_slice($items, 0, max(0, (int)$perSource));

            // Map a UI (sin acumular, con thumb)
            $bySource[$label] = array_map(function ($it) use ($label) {
                unset($it['ts']);
                return [
                    'id'          => substr(sha1($it['url']), 0, 16),
                    'sourceLabel' => $label,
                    'title'       => $it['title'],
                    'url'         => $it['url'],
                    'thumb'       => $it['thumb'] ?? null,
                    'date'        => $it['date'] ? $this->formatDate($it['date']) : null,
                ];
            }, $items);
        }

        // Agrupado sin mezclar
        if (!$interleave) {
            $all = [];
            foreach ($feeds as $label => $_) {
                $all = array_merge($all, $bySource[$label] ?? []);
            }
            return $all;
        }

        // Round-robin 1–1–1 (interleave)
        $queues = $bySource;
        $order  = array_keys($queues);
        $out    = [];
        while (!empty($order)) {
            foreach ($order as $i => $label) {
                if (!empty($queues[$label])) $out[] = array_shift($queues[$label]);
                if (empty($queues[$label]))  unset($order[$i]);
            }
            $order = array_values($order);
        }
        return $out;
    }

    /** Rotación diaria con gracia de N minutos; guarda stale 2 días para fallback controlado. */
    private function fetchDaily(string $url, bool $strictDaily = true, int $graceMin = 5): ?string
    {
        $tz    = config('app.timezone', 'UTC');
        $now   = Carbon::now($tz);
        $today = $now->toDateString();
        $minsSinceMidnight = $now->copy()->startOfDay()->diffInMinutes($now);

        $key   = 'videofeed:xml:'.md5($url);
        $entry = Cache::get($key); // ['body'=>..., 'day'=>'YYYY-MM-DD']

        // Si ya es del día → sirve
        if (is_array($entry) && ($entry['day'] ?? null) === $today) {
            return $entry['body'] ?? null;
        }

        $save = function (string $body) use ($key, $today) {
            Cache::put($key, [
                'body' => $body,
                'day'  => $today,
                'ts'   => Carbon::now()->timestamp,
            ], Carbon::now()->addDays(2)); // conserva stale por 48h
        };

        $try = function () use ($url) {
            try {
                $res = Http::timeout(12)->get($url);
                if ($res->successful() && $res->body() !== '') return $res->body();
            } catch (\Throwable $e) {}
            return null;
        };

        // Dentro de la ventana de gracia: intenta refrescar, si falla sirve stale de ayer
        if ($minsSinceMidnight <= max(0, (int)$graceMin)) {
            $body = $try();
            if ($body !== null) { $save($body); return $body; }
            return is_array($entry) ? ($entry['body'] ?? null) : null;
        }

        // Pasada la gracia → forzar refresh
        $body = $try();
        if ($body !== null) { $save($body); return $body; }

        // Falla post-gracia: ¿permitimos stale?
        if (!$strictDaily && is_array($entry)) {
            return $entry['body'] ?? null;
        }
        return null;
    }

    /**
     * Parse de Atom (YouTube) y RSS genérico.
     * Devuelve: [ ['title','url','thumb','date'], ... ]
     */
    private function parse(string $xml): array
    {
        $sx = @simplexml_load_string($xml);
        if (!$sx) return [];

        $ns = $sx->getNamespaces(true);
        $out = [];

        // === Atom (YouTube) ===
        if (isset($sx->entry)) {
            foreach ($sx->entry as $e) {
                $title = trim((string)($e->title ?? ''));
                $href  = '';
                foreach ($e->link ?? [] as $lnk) {
                    if ((string)($lnk['rel'] ?? '') === 'alternate' && !empty($lnk['href'])) {
                        $href = (string)$lnk['href'];
                        break;
                    }
                }

                // yt:videoId
                $videoId = null;
                if (!empty($ns['yt'])) {
                    $yt = $e->children($ns['yt']);
                    if (isset($yt->videoId)) $videoId = (string)$yt->videoId;
                }

                // media:thumbnail(s)
                $thumb = null;
                if (!empty($ns['media'])) {
                    $media = $e->children($ns['media']);
                    if (isset($media->group)) {
                        $thumbs = $media->group->thumbnail ?? null; // puede ser lista
                        if ($thumbs) {
                            $best = null; $bestArea = -1;
                            foreach ($thumbs as $t) {
                                $u = (string)($t['url'] ?? '');
                                $w = (int)($t['width'] ?? 0);
                                $h = (int)($t['height'] ?? 0);
                                $area = $w * $h;
                                if ($u && $area > $bestArea) { $best = $u; $bestArea = $area; }
                            }
                            if ($best) $thumb = $best;
                        }
                    }
                }

                // Fallback de thumbnail por videoId
                if (!$thumb && $videoId) {
                    $thumb = "https://i.ytimg.com/vi/{$videoId}/hqdefault.jpg";
                }
                // Fallback extra: intenta sacar v=... del href
                if (!$thumb && $href) {
                    $vid = $this->videoIdFromUrl($href);
                    if ($vid) $thumb = "https://i.ytimg.com/vi/{$vid}/hqdefault.jpg";
                }

                $date = (string)($e->updated ?? $e->published ?? '');

                if ($title !== '' && $href !== '') {
                    $out[] = ['title'=>$title, 'url'=>$href, 'thumb'=>$thumb, 'date'=>$date ?: null];
                }
            }
            return $out;
        }

        // === RSS genérico (por si mezclas otra fuente) ===
        if (isset($sx->channel->item)) {
            foreach ($sx->channel->item as $i) {
                $title = trim((string)($i->title ?? ''));
                $link  = (string)($i->link ?? '');
                $date  = (string)($i->pubDate ?? '');
                $thumb = null;

                // media:thumbnail en RSS
                if (!empty($ns['media'])) {
                    $media = $i->children($ns['media']);
                    if (isset($media->thumbnail)) {
                        // elegir el de mayor tamaño si hay varios
                        $best = null; $bestArea = -1;
                        foreach ($media->thumbnail as $t) {
                            $u = (string)($t['url'] ?? '');
                            $w = (int)($t['width'] ?? 0);
                            $h = (int)($t['height'] ?? 0);
                            $area = $w * $h;
                            if ($u && $area > $bestArea) { $best = $u; $bestArea = $area; }
                        }
                        if ($best) $thumb = $best;
                    }
                }

                if ($title !== '' && $link !== '') {
                    $out[] = ['title'=>$title, 'url'=>$link, 'thumb'=>$thumb, 'date'=>$date ?: null];
                }
            }
        }

        return $out;
    }

    private function videoIdFromUrl(string $u): ?string
    {
        $parts = parse_url($u);
        if (!$parts) return null;
        // watch?v=ID
        if (!empty($parts['query'])) {
            parse_str($parts['query'], $q);
            if (!empty($q['v']) && is_string($q['v'])) return $q['v'];
        }
        // youtu.be/ID
        if (!empty($parts['host']) && preg_match('~youtu\.be$~i', $parts['host']) && !empty($parts['path'])) {
            return ltrim($parts['path'], '/');
        }
        return null;
    }

    private function formatDate(?string $raw): ?string
    {
        if (!$raw) return null;
        try {
            return Carbon::parse($raw)->timezone(config('app.timezone'))->format('M j, Y');
        } catch (\Throwable) {
            return $raw;
        }
    }

    /** Helpers YouTube */
    public static function ytFeed(string $channelId): string
    {
        return "https://www.youtube.com/feeds/videos.xml?channel_id={$channelId}";
    }

    public static function ytFeedFromUrl(string $urlOrHandle): ?string
    {
        $u = trim($urlOrHandle);
        if (preg_match('~^(UC[0-9A-Za-z_-]{22,})$~', $u, $m)) return self::ytFeed($m[1]);
        if (!preg_match('~^https?://~i', $u)) $u = 'https://www.youtube.com/'.ltrim($u, '/');
        if (preg_match('~/channel/(UC[0-9A-Za-z_-]{22,})~', $u, $m)) return self::ytFeed($m[1]);

        try {
            $html = Http::timeout(10)->get($u)->body();
            foreach (['"channelId":"','"externalId":"','"browseId":"'] as $k) {
                if (preg_match('~'.preg_quote($k,'~').'(UC[0-9A-Za-z_-]{22,})"~', $html, $m)) {
                    return self::ytFeed($m[1]);
                }
            }
        } catch (\Throwable $e) {}
        return null;
    }
}
