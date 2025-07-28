<?php

namespace App\Filament\Member\Pages;

use App\Models\User;
use Filament\Pages\Page;

class BrowseUsers extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.member.pages.browse-users';

    protected static ?string $title = 'Members';
    
    protected ?string $heading = 'Members';
    
    public function getViewData(): array
    {
        $search = request()->input('search');
        $platform = request()->input('platform');

        $query = User::with('media');

        // búsqueda por nombre o bio
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', "%{$search}")
                    ->orWhere('bio', 'like', "%{$search}");
            });
        }

        // filtro por red social
        if ($platform) {
            $query->whereNotNull("social_links->{$platform}");
        }

        return [
            'users' => $query->paginate(10),
        ];
    }
}
