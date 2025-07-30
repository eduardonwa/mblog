<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Traits\GeneratesListPostHtml;

trait HandlesListPosts
{
    public function preparePostData(array $data): array
    {
        $data['meta_title'] = Str::limit($data['title'], 60, '');

        if ($data['post_template'] === 'post') {
            $data['extract'] = mb_substr(strip_tags($data['body']), 0, 300) . '...';
            $data['meta_description'] = Str::words(strip_tags($data['body']), 25, '...');
        }
        
        if ($data['post_template'] === 'lists') {
            $data['body'] = ''; // aseguramos que no intente usar body
            // autoasignar excerpt y meta_description con el intro
            $intro = $data['list_data_json']['intro'] ?? '';
            $data['extract'] = Str::words(strip_tags($intro), 30, '...');
            $data['meta_description'] = Str::words(strip_tags($intro), 25, '...');
            $data['list_data_html'] = GeneratesListPostHtml::renderListDataHtml($data['list_data_json']);
        }

        return $data;
    }
}