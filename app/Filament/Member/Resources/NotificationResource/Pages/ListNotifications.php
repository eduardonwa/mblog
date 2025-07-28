<?php

namespace App\Filament\Member\Resources\NotificationResource\Pages;

use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Member\Resources\NotificationResource;

class ListNotifications extends ListRecords
{
    protected static ?string $title = 'Notifications';

    protected static string $resource = NotificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }

    protected function getTableQuery(): Builder
    {
        return static::getModel()::query()
            ->where('notifiable_id', Auth::id())
            ->where('notifiable_type', get_class(Auth::user()));
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
