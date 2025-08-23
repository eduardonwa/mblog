<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class NewsFeed
{
    /**
     * Junta noticias de varios feeds (Atom/RSS) dejando sólo: title, url, date.
     *
     * @param array<string,string> $feeds  // ["Label" => "https://...feed"]
     * @param array{per_source?:int, interleave?:bool, ttl_minutes?:int} $opts
     * @return array<int,array{id:string, sourceLabel:string, title:string, url:string, date:?string}>
     */
    public function collect(array $feeds, array $opts = []): array
    {
        $perSource   = $opts['per_source']   ?? 10;   // cuántos por sello
        $interleave  = $opts['interleave']   ?? true; // mezclar 1–1–1
        $ttlMinutes  = $opts['ttl_minutes']  ?? 60;   // cache del XML

        $bySource = [];

        foreach ($feeds as $label => $url) {
            $xml = $this->fetch($url, $ttlMinutes);
            if ($xml === null) {
                $bySource[$label] = [];
                continue;
            }
            $items = $this->parse($xml);

            // Ordena por fecha desc y corta por sello
            usort($items, fn($a,$b) =>
                strtotime($b['date'] ?? '1970-01-01') <=> strtotime($a['date'] ?? '1970-01-01')
            );

            $items = array_slice($items, 0, max(0, (int)$perSource));

            // Normaliza shape final
            $bySource[$label] = array_map(function ($it) use ($label) {
                return [
                    'id'          => substr(sha1($it['url']), 0, 16),
                    'sourceLabel' => $label,
                    'title'       => $it['title'],
                    'url'         => $it['url'],
                    'date'        => $this->formatDate($it['date']),
                ];
            }, $items);
        }

        // Mezcla o concatena
        if (!$interleave) {
            $all = [];
            foreach ($feeds as $label => $_) {
                $all = array_merge($all, $bySource[$label] ?? []);
            }
            return $all;
        }

        // Interleaving round-robin
        $queues = $bySource;
        $order  = array_keys($queues);
        $out    = [];
        while (!empty($order)) {
            foreach ($order as $i => $label) {
                if (!empty($queues[$label])) {
                    $out[] = array_shift($queues[$label]);
                }
                if (empty($queues[$label])) {
                    unset($order[$i]);
                }
            }
            $order = array_values($order);
        }
        return $out;
    }

    /**
     * Descarga el feed con caché simple por TTL.
     */
    private function fetch(string $url, int $ttlMinutes): ?string
    {
        $cacheKey = 'feedxml:'.md5($url);
        return Cache::remember($cacheKey, now()->addMinutes($ttlMinutes), function () use ($url) {
            try {
                $res = Http::timeout(12)->get($url);
                if ($res->successful() && Str::contains($res->header('Content-Type', ''), ['xml','atom','rss','application/xml','text/xml'])) {
                    return $res->body();
                }
                // A veces no envían content-type; igual aceptamos si hay cuerpo.
                if ($res->successful() && $res->body() !== '') {
                    return $res->body();
                }
            } catch (\Throwable $e) {
                // swallow y regresa null (esa fuente se salta)
            }
            return null;
        });
    }

    /**
     * Parse básico para Atom y RSS (sólo title/url/date).
     *
     * @return array<int,array{title:string,url:string,date:?string}>
     */
    private function parse(string $xml): array
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->preserveWhiteSpace = false;
        \libxml_use_internal_errors(true);
        $ok = $dom->loadXML($xml, LIBXML_NOERROR | LIBXML_NOWARNING);
        \libxml_clear_errors();
        if (!$ok) return [];

        $xp = new \DOMXPath($dom);
        $xp->registerNamespace('atom', 'http://www.w3.org/2005/Atom');
        $xp->registerNamespace('content', 'http://purl.org/rss/1.0/modules/content/');
        $xp->registerNamespace('dc', 'http://purl.org/dc/elements/1.1/');

        $isAtom = $dom->documentElement && $dom->documentElement->namespaceURI === 'http://www.w3.org/2005/Atom';
        $out = [];

        if ($isAtom) {
            foreach ($xp->query('//atom:entry') as $e) {
                $title = trim((string)$xp->evaluate('string(atom:title)', $e));
                $link  = trim((string)$xp->evaluate('string(atom:link[@rel="alternate"]/@href | atom:link[not(@rel)]/@href)', $e));
                $date  = trim((string)$xp->evaluate('string(atom:published | atom:updated)', $e));
                if ($title === '' || $link === '') continue;
                $out[] = ['title'=>$title, 'url'=>$link, 'date'=>$date ?: null];
            }
        } else {
            foreach ($xp->query('//item') as $e) {
                $title = trim((string)$xp->evaluate('string(title)', $e));
                $link  = trim((string)$xp->evaluate('string(link)', $e));
                $guid  = trim((string)$xp->evaluate('string(guid)', $e));
                $date  = trim((string)$xp->evaluate('string(pubDate | dc:date)', $e));
                // fallback del link: si no hay <link> usa guid si parece URL
                if ($link === '' && preg_match('~^https?://~i', $guid)) $link = $guid;
                if ($title === '' || $link === '') continue;
                $out[] = ['title'=>$title, 'url'=>$link, 'date'=>$date ?: null];
            }
        }

        return $out;
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
}
