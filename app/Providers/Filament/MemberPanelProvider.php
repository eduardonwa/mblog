<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use App\Filament\Member\Pages\MemberDashboard;
use App\Filament\Member\Pages\Auth\EditProfile;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class MemberPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('member')
            ->path('member')
            ->colors([
                // verde
                /* 'primary' => [
                    50 => '244, 255, 199',
                    100 => '232, 255, 149',
                    200 => '208, 255, 68',
                    300 => '191, 246, 37',
                    400 => '160, 221, 5',
                    500 => '123, 177, 0',
                    600 => '93, 134, 5',
                    700 => '75, 105, 11',
                    800 => '63, 89, 14',
                ], */
                // turquesa
                /* 'gray' => [
                    50 => '229, 243, 249',
                    100 => '186, 226, 238',
                    200 => '148, 212, 229',
                    300 => '90, 190, 214',
                    400 => '53, 166, 194',
                    500 => '37, 135, 164',
                    600 => '31, 108, 133',
                    700 => '30, 76, 92',
                    800 => '20, 50, 61',
                ], */
            ])
            ->discoverResources(in: app_path('Filament/Member/Resources'), for: 'App\\Filament\\Member\\Resources')
            ->discoverPages(in: app_path('Filament/Member/Pages'), for: 'App\\Filament\\Member\\Pages')
            ->pages([
                MemberDashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Member/Widgets'), for: 'App\\Filament\\Member\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->viteTheme('resources/css/filament/member/theme.css')
            ->spa()
            ->topNavigation()
            ->profile(EditProfile::class)
            ->userMenuItems([
                'profile' => MenuItem::make()->label('Edit profile'),
                'redirect' => MenuItem::make()
                    ->label('Back to site')
                    ->url('/')
                    ->icon('heroicon-o-arrow-left')
            ])
            ;
    }
}
