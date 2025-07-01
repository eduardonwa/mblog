<?php

namespace App\Filament\Member\Resources\PostResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Member\Resources\PostResource;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['meta_title'] = Str::limit($data['title'], 60, '');
        $data['meta_description'] = Str::words(strip_tags($data['body']), 25, '...');
        
        return $data;
    }
}
