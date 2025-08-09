<?php

namespace App\Filament\Resources\PostSeriesResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\PostSeriesResource;

class ManagePostSeries extends ManageRecords
{
    protected static string $resource = PostSeriesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}