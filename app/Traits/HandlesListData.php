<?php
namespace App\Traits;

use Illuminate\Support\Str;
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
                    'resource' => Purify::clean($item['resource'] ?? ''),
                    'description' => Purify::clean($item['description'] ?? ''),
                ];
            })->toArray();
        $outro = Purify::clean($data['outro'] ?? '');

        $listHtml = collect($items)->map(fn ($item) => <<<HTML
            <li>
                <h3>{$item['title']}</h3>
                <a href="{$item['resource']}" target="_blank">Listen</a>
                <p>{$item['description']}</p>
            </li>
        HTML)->join('');

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
}