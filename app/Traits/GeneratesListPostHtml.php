<?php

namespace App\Traits;

class GeneratesListPostHtml
{
    public static function renderListDataHtml(array $data): string
    {
        $html = "<div class='list-post'>";

        if (!empty($data['intro'])) {
            $html .= "<div class='intro'>" . nl2br(e($data['intro'])) . "</div>";
        }

        foreach ($data['items'] as $item) {
            $html .= "<div class='list-item'>";
            $html .= "<h3 class='resource-title'>" . e($item['title']) . "</h3>";
            $html .= "<p class='resource-description'>" . nl2br(e($item['description'])) . "</p>";
            $html .= "<div class='resource-item'>" . self::renderTiptapResource($item['resource']) . "</div>";
            $html .= "</div>";
        }

        if (!empty($data['outro'])) {
            $html .= "<div class='outro'>" . nl2br(e($data['outro'])) . "</div>";
        }

        $html .= "</div>";

        return $html;
    }

    public static function renderTiptapResource($resource)
    {
        // Si es array o JSON válido: recorre nodos
        if (is_array($resource) || (is_string($resource) && self::isJson($resource))) {
            if (is_string($resource)) {
                $resourceJson = json_decode($resource, true);
            } else {
                $resourceJson = $resource;
            }

            if (!$resourceJson || !isset($resourceJson['content'])) {
                return '';
            }

            $html = '';
            foreach ($resourceJson['content'] as $node) {
                // Bandcamp
                if ($node['type'] === 'bandcampIframe') {
                    $attrs = $node['attrs'] ?? [];
                    $src = $attrs['src'] ?? '';
                    $style = $attrs['style'] ?? '';
                    $seamless = !empty($attrs['seamless']) ? 'seamless' : '';
                    $html .= "<iframe src=\"{$src}\" style=\"{$style}\" width=\"100%\" height=\"120px\" frameborder=\"0\" {$seamless} allow=\"encrypted-media\"></iframe>";
                }
                
                // Youtube
                if ($node['type'] === 'youtube' && isset($node['attrs']['src'])) {
                    $src = $node['attrs']['src'];
                    $style = $node['attrs']['style'] ?? '';
                    $html .= "<iframe src=\"{$src}\" style=\"{$style}\" width=\"100%\" height=\"400px\" frameborder=\"0\" allowfullscreen></iframe>";
                }
            }
            return $html;
        }

        // Si es HTML plano: retorna como está
        if (is_string($resource)) {
            return $resource;
        }

        return '';
    }

    public static function isJson($string)
    {
        if (!is_string($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

}