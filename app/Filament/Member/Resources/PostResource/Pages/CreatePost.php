<?php

namespace App\Filament\Member\Resources\PostResource\Pages;

use Illuminate\Support\Str;
use App\Traits\HandlesListPosts;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Member\Resources\PostResource;

class CreatePost extends CreateRecord
{    
    use HandlesListPosts;
    
    protected static string $resource = PostResource::class;

    public static string | Alignment $formActionsAlignment = Alignment::Center;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['list_data_json']['items']) && is_array($data['list_data_json']['items'])) {
            $data['list_data_json']['items'] = array_values($data['list_data_json']['items']);
        }

        return $this->preparePostData($data);
    }
}
