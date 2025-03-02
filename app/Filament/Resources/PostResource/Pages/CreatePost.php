<?php

namespace App\Filament\Resources\PostResource\Pages;

use Filament\Actions;
use Filament\Support\Enums\Alignment;
use App\Filament\Resources\PostResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePost extends CreateRecord
{
    protected static string $resource = PostResource::class;
    public static string | Alignment $formActionsAlignment = Alignment::Right;
}
