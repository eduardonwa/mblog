<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\Alignment;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\EditRecord;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;
    public static string | Alignment $formActionsAlignment = Alignment::Right;
    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
