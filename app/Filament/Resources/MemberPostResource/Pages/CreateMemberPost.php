<?php

namespace App\Filament\Resources\MemberPostResource\Pages;

use App\Filament\Resources\MemberPostResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMemberPost extends CreateRecord
{
    protected static string $resource = MemberPostResource::class;
}
