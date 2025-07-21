<?php

namespace App\Filament\Member\Resources\PostResource\Pages;

use Illuminate\Support\Str;
use App\Traits\HandlesListData;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Member\Resources\PostResource;

class CreatePost extends CreateRecord
{    
    protected static string $resource = PostResource::class;

    public static string | Alignment $formActionsAlignment = Alignment::Center;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['meta_title'] = Str::limit($data['title'], 60, '');
        $data['meta_description'] = Str::words(strip_tags($data['body']), 25, '...');
        
        return $data;
    }
}
