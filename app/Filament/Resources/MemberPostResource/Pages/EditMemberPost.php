<?php

namespace App\Filament\Resources\MemberPostResource\Pages;

use App\Filament\Resources\MemberPostResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMemberPost extends EditRecord
{
    protected static string $resource = MemberPostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
