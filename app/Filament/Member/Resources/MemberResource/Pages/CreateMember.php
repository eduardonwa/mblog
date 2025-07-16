<?php

namespace App\Filament\Member\Resources\MemberResource\Pages;

use App\Filament\Member\Resources\MemberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMember extends CreateRecord
{
    protected static string $resource = MemberResource::class;
}
