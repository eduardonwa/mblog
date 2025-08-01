<?php

namespace App\Filament\Resources\MemberPostResource\Pages;

use App\Filament\Resources\MemberPostResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMemberPosts extends ListRecords
{
    protected static string $resource = MemberPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
