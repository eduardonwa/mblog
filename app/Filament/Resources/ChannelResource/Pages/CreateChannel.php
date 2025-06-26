<?php

namespace App\Filament\Resources\ChannelResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\Alignment;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ChannelResource;

class CreateChannel extends CreateRecord
{
    protected static string $resource = ChannelResource::class;
    public static string | Alignment $formActionsAlignment = Alignment::Right;
}
