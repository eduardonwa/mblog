<?php

namespace App\Filament\Resources\ChannelResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ChannelResource;

class EditChannel extends EditRecord
{
    protected static string $resource = ChannelResource::class;
    public static string | Alignment $formActionsAlignment = Alignment::Right;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
