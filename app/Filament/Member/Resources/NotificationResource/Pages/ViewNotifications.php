<?php

namespace App\Filament\Member\Resources\NotificationResource\Pages;

use App\Filament\Member\Resources\NotificationResource;
use Filament\Resources\Pages\Page;

class ViewNotifications extends Page
{
    protected static string $resource = NotificationResource::class;

    protected static string $view = 'filament.member.resources.notification-resource.pages.view-notifications';
}
