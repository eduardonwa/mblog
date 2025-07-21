<?php

namespace App\Traits;

use App\Traits\HtmlHelper;
use Illuminate\Support\Str;
use App\Traits\GeneratesListPostHtml;

trait HandlesListPosts
{
    public function preparePostData(array $data): array
    {
        $data['meta_title'] = Str::limit($data['title'], 60, '');

        if ($data['post_template'] === 'post') {
            $data['meta_description'] = Str::words(strip_tags($data['body']), 25, '...');
        }

        if ($data['post_template'] === 'list') {
            $data['body'] = ''; // aseguramos que no intente usar body
            $data['meta_description'] = Str::words(strip_tags($data['list_data']['intro'] ?? ''), 25, '...');
            $data['list_data_html'] = GeneratesListPostHtml::renderListDataHtml($data['list_data_json']);
        }

        return $data;
    }
}