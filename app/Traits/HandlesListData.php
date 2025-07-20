<?php
namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Stevebauman\Purify\Facades\Purify;

trait HandlesListData
{
    protected function processListData(array $data): array
    {
        $intro = Purify::clean($data['intro'] ?? '');
        
        $items = collect($data['items'] ?? [])
            ->map(function ($item) {
                return [
                    'title' => Purify::clean($item['title'] ?? ''),
                    'resource' => $item['resource'] ?? null,
                    'description' => Purify::clean($item['description'] ?? ''),
                ];
            })->toArray();

        $outro = Purify::clean($data['outro'] ?? '');
        
        $listHtml = collect($items)->map(function ($item) {
            return '<li>
                <h3>' . $item['title'] . '</h3>
                ' . $item['resource'] . '
                <p>' . $item['description'] . '</p>
            </li>';
        })->join('');

        $html = <<<HTML
            <div class="metal-list">
                <p class="intro">{$intro}</p>
                <ul>{$listHtml}</ul>
                <p class="outro">{$outro}</p>
            </div>
        HTML;
                
        return [
            'list_data_html' => $html,
            'meta_description' => Str::words(strip_tags($intro), 25, '...'),
            'list_data_json' => $data,
        ];
    }

    protected function cleanResources(array $listDataJson): array
    {
        if (!empty($listDataJson['items'])) {
            foreach ($listDataJson['items'] as &$item) {
                if (!empty($item['resource'])) {
                    preg_match('/<iframe[^>]+src="([^"]+)"/', $item['resource'], $matches);
                    $src = $matches[1] ?? null;

                    if ($src && str_contains($src, 'youtube.com/embed')) {
                        $item['resource'] = $src;
                    } else {
                        $item['resource'] = null;
                    }
                }
            }
        }
        return $listDataJson;
    }
}