<?php

namespace App\Filament\Member\Resources\PostResource\Pages;

use Illuminate\Support\Str;
use App\Traits\HandlesListData;
use Filament\Support\Enums\Alignment;
use Stevebauman\Purify\Facades\Purify;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Member\Resources\PostResource;

class CreatePost extends CreateRecord
{
    use HandlesListData;
    
    protected static string $resource = PostResource::class;

    public static string | Alignment $formActionsAlignment = Alignment::Center;
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (!empty($data['list_data_json'])) {
            $data['list_data_json'] = $this->cleanResources($data['list_data_json']);
        }
        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (!empty($data['list_data_json'])) {
            $data['list_data_json'] = $this->cleanResources($data['list_data_json']);
        }
        
        $data['meta_title'] = Str::limit($data['title'], 60, '');

        if (($data['post_template'] ?? 'post') === 'list') {
            $processed = $this->processListData($data['list_data'] ?? []);
            $data['list_data_html'] = $processed['list_data_html'];
            $data['meta_description'] = $processed['meta_description'];
            $data['list_data_json'] = $processed['list_data_json'];

            $data['body'] = '';
            unset($data['list_data']);
        } else {
            $data['meta_description'] = Str::words(strip_tags($data['body'] ?? ''), 25, '...');
        }

        return $data;
    }

    
}
