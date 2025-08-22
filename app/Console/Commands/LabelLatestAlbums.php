<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Services\MusicBrainz\MbClient;
use App\Services\MusicBrainz\LabelReleases;
use App\Services\MusicBrainz\CoverArt;

class LabelLatestAlbums extends Command
{
    protected $signature = 'mbz:label-latest-albums 
        {label : Nombre del sello, ej. "Century Media"} 
        {--take=20 : Cuántos albums traer} 
        {--covers : Intentar portada (Cover Art Archive)} 
        {--cache-key= : Clave de cache personalizada} 
        {--ttl=30 : TTL de cache en minutos}';

    protected $description = 'Trae los últimos N álbumes de un label desde MusicBrainz, opcionalmente agrega portada y cachea el resultado';

    public function handle(MbClient $mb, LabelReleases $lr)
    {
        $label = (string)$this->argument('label');
        $take  = (int)$this->option('take');
        $withCovers = (bool)$this->option('covers');
        $ttlMinutes = (int)$this->option('ttl');

        $this->info("🔎 Buscando MBID para: {$label}");
        $mbid = $mb->findLabelMbid($label);
        if (!$mbid) {
            $this->error("No encontré MBID para '{$label}'");
            return Command::FAILURE;
        }
        $this->info("✅ MBID: {$mbid}");

        $this->info("📥 Consultando últimos {$take} álbumes (desde hoy hacia atrás)...");
        $albums = $lr->latestAlbumsByLabelFromToday($mbid, $take);

        if ($withCovers) {
            $this->info("🖼  Resolviendo portadas en Cover Art Archive...");
            $cover = new CoverArt();
            foreach ($albums as $i => &$r) {
                $r['cover_url'] = $cover->front($r['rgid'] ?? null, $r['rid'] ?? null);
                usleep(180000); // ~0.18s entre HEADs para no saturar
                $this->line(sprintf("  [%02d/%02d] %s — %s%s",
                    $i+1, count($albums), $r['band'], $r['title'], $r['cover_url'] ? ' (ok)' : ' (sin portada)'
                ));
            }
            unset($r);
        }

        // Cache
        $cacheKey = $this->option('cache-key') ?: "mbz:label:{$mbid}:latest:albums:{$take}".($withCovers?':covers':'');
        Cache::put($cacheKey, $albums, now()->addMinutes($ttlMinutes));
        $this->info("🗄  Guardados en cache: {$cacheKey} (TTL {$ttlMinutes} min)");

        // Imprimir un resumen en consola
        $this->line('');
        $headers = ['Fecha','Banda','Álbum','País','Tipo'];
        if ($withCovers) {
            $headers[] = 'Cover?';
        }

        $rows = collect($albums)->map(function ($r) use ($withCovers) {
            $row = [
                $r['date']    ?? '-',
                $r['band']    ?? '-',
                $r['title']   ?? '-',
                $r['country'] ?? '-',
                $r['type']    ?? '-',
            ];
            if ($withCovers) {
                $row[] = !empty($r['cover_url']) ? '✓' : '—';
            }
            return $row;
        })->all();

        $this->table($headers, $rows);

        return Command::SUCCESS;
    }
}
