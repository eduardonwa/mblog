<?php

namespace App\Console\Commands;

use GuzzleHttp\Client;
use Illuminate\Support\Str;
use GuzzleHttp\Promise\Utils;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ScrapeMetalArchives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:metal-archives';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap metalarchives.com for the latest releases and save them as cache.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting scraper...');

        try {
            $response = Http::withoutVerifying()->get('https://www.metal-archives.com/release/ajax-upcoming/json/1');
            $data = $response->json();

            $client = new Client(['verify' => false]);
            $albumsData = collect($data['aaData'])->take(20);

            // prepara las promesas para cada album
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

            // Ejecuta todas las peticiones en paralelo
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
            $albumsWithCover = collect($albums)->whereNotNull('cover')->count();
            $this->info("From " . count($albums) . " albums, $albumsWithCover had cover art.");
            $this->info("🎸" . count($albums) . " albums found");
            $this->info('Memory usage: ' . round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB');
            Cache::put('metal.new_releases', $albums, now()->addHours(6));
            $this->info('Albums saved in cache.');
        } catch (\Exception $e) {
            $this->error('Error ' . $e->getMessage());
            Log::error('Scrapper error', ['error' => $e->getMessage()]);
        }
    }
}
