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
            $html .= "<h3>" . e($item['title']) . "</h3>";

            // Aquí va tu iframe o embed ya validado/controlado
            $html .= "<div class='resource'>" . $item['resource'] . "</div>";

            $html .= "<p>" . nl2br(e($item['description'])) . "</p>";
            $html .= "</div>";
        }

        if (!empty($data['outro'])) {
            $html .= "<div class='outro'>" . nl2br(e($data['outro'])) . "</div>";
        }

        $html .= "</div>";

        return $html;
    }
}
