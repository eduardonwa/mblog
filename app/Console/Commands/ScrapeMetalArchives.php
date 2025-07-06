<?php

namespace App\Console\Commands;

use App\Services\MetalReleases;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScrapeMetalArchives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scrape:metal-archives {--offset=0}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrap metalarchives.com for the latest releases and save them as cache.';
    
    /**
     * Execute the console command.
     */
    public function handle(MetalReleases $scraper)
    {
        $offset = (int) $this->option('offset');
    
        try {
            Log::info('🧲 Iniciando servicio MetalArchivesScraper a las ' . now()->toDateTimeString());
            
            $albums = $scraper->scrape($offset);

            $albumsWithCover = collect($albums)->whereNotNull('cover')->count();
            Log::info("🎸 Se encontraron " . count($albums) . " álbumes, con $albumsWithCover portadas.");
            Log::info('💾 Uso de memoria: ' . round(memory_get_peak_usage(true) / 1024 / 1024, 2) . ' MB');
        } catch (\Exception $e) {
            $this->error('💥 Error: ' . $e->getMessage());
            Log::error('Scraper error', ['error' => $e->getMessage()]);
        }
    }
}
