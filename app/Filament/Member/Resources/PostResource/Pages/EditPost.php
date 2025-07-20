<?php

namespace App\Filament\Member\Resources\PostResource\Pages;

use Filament\Actions;
use Illuminate\Support\Str;
use App\Traits\HandlesListData;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Member\Resources\PostResource;
use Pboivin\FilamentPeek\Pages\Concerns\HasPreviewModal;

class EditPost extends EditRecord
{
    use HasPreviewModal, HandlesListData;

    protected static string $resource = PostResource::class;
    
    public static string | Alignment $formActionsAlignment = Alignment::Center;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            \Pboivin\FilamentPeek\Pages\Actions\PreviewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // aqui hacemos la conversion de list_data_json a list_data
        if (($data['post_template'] ?? 'post') === 'list' && !empty($data['list_data_json'])) {
            $data['list_data'] = $data['list_data_json'] ?? [];
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['meta_title'] = Str::limit($data['title'], 60, '');

        if (($data['post_template'] ?? 'post') === 'list') {
            $processed = $this->processListData($data['list_data'] ?? []);
            $data['list_data_html'] = $processed['list_data_html'];
            $data['meta_description'] = $processed['meta_description'];
            $data['list_data_json'] = $processed['list_data_json'];

            unset($data['list_data']);
        } else {
            $data['meta_description'] = Str::words(strip_tags($data['body'] ?? ''), 25, '...');
            $data['list_data_html'] = '';
        }

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
