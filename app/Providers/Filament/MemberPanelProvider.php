<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Navigation\MenuItem;
use Filament\Http\Middleware\Authenticate;
use Pboivin\FilamentPeek\FilamentPeekPlugin;
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
            ->plugins([
                FilamentPeekPlugin::make()
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
            ->brandLogo(fn () => view('filament.member.logo'))
            ->renderHook('panels::body.end', function () {
                return view('components.modal-bandcamp');
            });
    }
}
