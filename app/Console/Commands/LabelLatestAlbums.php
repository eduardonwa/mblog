<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\MusicBrainz\CoverArt;
use App\Services\MusicBrainz\MbClient;
use App\Services\MusicBrainz\LabelReleases;

class LabelLatestAlbums extends Command
{
    protected $signature = 'mbz:label-latest-albums 
        {label : Nombre del sello, ej. "Century Media"} 
        {--take=20 : Cuántos albums traer} 
        {--covers : Intentar portada (Cover Art Archive)} 
        {--offset=0 : Bloque activo a escribir (0|20|40...)}';

    protected $description = 'Trae los últimos N álbumes de un label desde MusicBrainz, opcionalmente agrega portada y cachea el resultado';
    
    protected function http()
    {
        return Http::retry(3, 800)->withHeaders([
            'User-Agent' => 'sickofmetal/1.0 (+https://sickofmetal.net; admin@sickofmetal.net)',
            'Accept'     => 'application/json',
        ]);
    }

    public function handle(MbClient $mb, LabelReleases $lr)
    {
        $label       = (string) $this->argument('label');
        $take        = (int) $this->option('take');
        $withCovers  = (bool) $this->option('covers');

        // 1) Resolver MBID del label
        $this->info("🔎 Buscando MBID para: {$label}");
        $mbid = $mb->findLabelMbid($label);
        if (!$mbid) {
            $this->error("No encontré MBID para '{$label}'");
            return Command::FAILURE;
        }
        $this->info("✅ MBID: {$mbid}");

        // 2) Traer últimos N álbumes
        $this->info("📥 Consultando últimos {$take} álbumes (desde hoy hacia atrás)...");
        $albums = $lr->latestAlbumsByLabelFromToday($mbid, $take);

        // 3) Obtener la fecha
        $this->info("🗓️ Formateando fechas en español...");
        foreach ($albums as &$r) {
            $r['date_text'] = $this->formatDateEs($r['date'] ?? null);
        }
        unset($r);
        
        // 4) (Opcional) Portadas
        if ($withCovers) {
            $this->info("🖼  Resolviendo portadas en Cover Art Archive...");
            $cover = new CoverArt();
            foreach ($albums as $i => &$r) {
                $r['cover_url'] = $cover->front($r['rgid'] ?? null, $r['rid'] ?? null);
                usleep(180000); // ~0.18s entre HEADs para no saturar
                $this->line(sprintf(
                    "  [%02d/%02d] %s — %s%s",
                    $i+1,
                    count($albums),
                    $r['band'],
                    $r['title'],
                    !empty($r['cover_url']) ? ' (ok)' : ' (sin portada)'
                ));
            }
            unset($r);
        }

        // 5) Género (único) por release-group, con fallback si no hay
        if (!empty($albums)) {
            $this->info("🎶 Resolviendo género (release-group) desde MusicBrainz...");
            $defaultGenre = '—';

            foreach ($albums as $i => &$r) {
                $r['genre'] = $defaultGenre; // inicializa con fallback

                $rgid = $r['rgid'] ?? null;
                if ($rgid) {
                    $res = $this->http()->get("https://musicbrainz.org/ws/2/release-group/{$rgid}", [
                        'inc' => 'genres',
                        'fmt' => 'json',
                    ]);

                    if ($res->ok()) {
                        $genres = $res->json('genres') ?? [];

                        // Ordena por 'count' desc para tomar el más votado
                        usort($genres, function ($a, $b) {
                            return ($b['count'] ?? 0) <=> ($a['count'] ?? 0);
                        });

                        // Toma el primer nombre disponible
                        $top = $genres[0]['name'] ?? null;
                        if (!empty($top)) {
                            $r['genre'] = (string) $top;
                        }
                    }

                    usleep(180000); // respeta rate limit
                    $this->line(sprintf(
                        "  [%02d/%02d] %s — %s [%s]",
                        $i+1, count($albums), $r['band'], $r['title'], $r['genre']
                    ));
                }
            }
            unset($r);
        }

        // 6) Guardar en caché (mismo bloque siempre)
        $store = Cache::store('metal-scraper');
        
        $expiresAt = $this->nextSlotExpiresAt()->addMinutes(5); // buffer de 5 min
        
        $store->put('metal.new_releases.block_0', $albums, $expiresAt);
        $store->put('metal.new_releases.active_block', 0, $expiresAt);

        // 7) Resumen en consola
        $this->line('');
        $headers = ['Fecha','Banda','Géneros','Álbum','País','Tipo'];
        if ($withCovers) {
            $headers[] = 'Cover?';
        }

        $rows = collect($albums)->map(function ($r) use ($withCovers) {
            $row = [
                $r['date_text']    ?? '-',
                $r['band']    ?? '-',
                $r['genre']  ?? '-',
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

    protected function formatDateEs(?string $date): string
    {
        if (!$date) return '—';
        try {
            $dt = \Carbon\Carbon::parse($date)->locale('en');

            // Heurística: si quedó como 01-01, asumimos que solo había año;
            // si quedó como día 01, asumimos que solo había año-mes.
            if (preg_match('/^\d{4}-01-01$/', $date)) {
                return $dt->isoFormat('YYYY');              // 2025
            }
            if (preg_match('/^\d{4}-\d{2}-01$/', $date)) {
                return $dt->isoFormat('MMM YYYY');          // ago 2025
            }

            return $dt->isoFormat('D MMM YYYY');       // 22 de ago 2025
        } catch (\Throwable $e) {
            return '—';
        }
    }

    protected function nextSlotExpiresAt(): Carbon
    {
        $tz   = 'America/Mazatlan';
        $now  = Carbon::now($tz);
        $slots = [[7,0],[14,0],[21,0]];
        foreach ($slots as [$h,$m]) {
            $t = $now->copy()->setTime($h,$m,0);
            if ($t->greaterThan($now)) return $t;
        }
        return $now->copy()->addDay()->setTime(7,0,0);
    }
}
