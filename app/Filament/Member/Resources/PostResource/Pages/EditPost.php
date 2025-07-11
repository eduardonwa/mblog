<?php

namespace App\Filament\Member\Resources\PostResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Member\Resources\PostResource;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreviewModal;

    protected static string $resource = PostResource::class;
    
    public static string | Alignment $formActionsAlignment = Alignment::Center;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            \Pboivin\FilamentPeek\Pages\Actions\PreviewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['meta_title'] = Str::limit($data['title'], 60, '');
        $data['meta_description'] = Str::words(strip_tags($data['body']), 25, '...');
        
        return $data;
    }

    protected function getPreviewModalView(): ?string
    {
        return 'post.preview';
    }

    protected function getPreviewModalDataRecordKey(): ?string
    {
        return 'post';
    }
}
