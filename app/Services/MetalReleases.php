<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use GuzzleHttp\Client;
use GuzzleHttp\Promise\Utils;
use Illuminate\Support\Facades\Http;

class MetalReleases
{
    public function scrape(int $offset = 0)
    {
        $response = Http::withoutVerifying()->get('https://www.metal-archives.com/release/ajax-upcoming/json/1');
        $data = $response->json();

        $client = new Client(['verify' => false]);
        $albumsData = collect($data['aaData'])->slice($offset)->take(20)->values();

        $promises = [];
        $albumUrls = [];

        foreach ($albumsData as $row) {
            $releaseHtml = $row[1] ?? '';
            $albumUrl = null;
            if (preg_match('/<a[^>]+href="([^"]+)"/', $releaseHtml, $urlMatch)) {
                $albumUrl = $urlMatch[1];
                $albumUrls[] = $albumUrl;
                $promises[] = $client->getAsync($albumUrl);
            } else {
                $albumUrls[] = null;
                $promises[] = null;
            }
        }

        $responses = Utils::settle($promises)->wait();
        $albums = [];

        foreach ($albumsData as $i => $row) {
            $cover = null;
            $albumUrl = $albumUrls[$i];
            $releaseHtml = $row[1] ?? '';
            $response = $responses[$i]['value'] ?? null;

            if ($albumUrl && $response) {
                $albumPage = (string) $response->getBody();
                if (preg_match('/<a[^>]+class="image"[^>]+id="cover"[^>]+href="([^"]+)"/', $albumPage, $coverMatch)) {
                    $cover = $coverMatch[1];
                    if (str_starts_with($cover, '/')) {
                        $cover = 'https://www.metal-archives.com' . $cover;
                    }
                }
            }

            if (!$cover) {
                $cover = asset('images/no-album-cover.webp');
            }
            
            $genre = $row[3] ?? '';
            $genre = Str::limit($genre, 22, '...');

            $albums[] = [
                'band' => strip_tags($row[0] ?? ''),
                'releaseTitle' => strip_tags($releaseHtml),
                'type' => $row[2] ?? '',
                'cover' => $cover,
                'releaseDate' => $row[4] ?? '',
                'addedDate' => $row[5] ?? '',
                'genre' => $genre,
                'albumUrl' => $albumUrl,
            ];
        }

        $metalCache = Cache::store('metal-scraper');

        // limpiar si es bloque 0
        if ($offset === 0) {
            $metalCache->forget('metal.new_releases.block_0');
            $metalCache->forget('metal.new_releases.block_20');
            $metalCache->forget('metal.new_releases.block_40');
            $metalCache->forget('metal.new_releases.all');
        }

        // Guardar este bloque y marcar como activo
        $metalCache->put("metal.new_releases.block_{$offset}", $albums, now()->addHours(6));
        $metalCache->put("metal.new_releases.active_block", $offset, now()->addHours(6));

        return $albums;
    }
}