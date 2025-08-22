<?php

namespace App\Services\MusicBrainz;

use Illuminate\Support\Facades\Http;

class CoverArt
{
    protected function try(string $url): ?string {
        $res = Http::withHeaders(['User-Agent'=>'mblog/1.0 (+https://tu-dominio; contacto@tu-dominio)'])
                   ->timeout(8)->head($url);
        return $res->ok() ? $url : null;
    }

    public function front(?string $rgid, ?string $rid): ?string {
        if ($rgid) {
            if ($u = $this->try("https://coverartarchive.org/release-group/{$rgid}/front-250")) return $u;
        }
        if ($rid) {
            if ($u = $this->try("https://coverartarchive.org/release/{$rid}/front-250")) return $u;
        }
        return null;
    }
}
