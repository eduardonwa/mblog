<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Observers\PostObserver;
use App\Observers\UserObserver;
use Filament\Support\Assets\Js;
use BeyondCode\Comments\Comment;
use App\Filament\MyLoginResponse;
use App\Filament\MyLogoutResponse;
use Illuminate\Support\Facades\Vite;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Filament\Http\Responses\Auth\Contracts\LoginResponse;
use Filament\Http\Responses\Auth\Contracts\LogoutResponse;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Model::preventLazyLoading(! app()->isProduction());

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Verify your account at ' . config('app.name'))
                ->line('Click the link below to verify your email')
                ->action('Verify Email', $url);
        });

        // solo usuarios autenticados pueden comentar
        Comment::creating(function ($comment) {
            if (!$comment->commentator->hasVerifiedEmail()) {
                return false; // Rechazar silenciosamente
            }
        });

        User::observe(UserObserver::class);
        Post::observe(PostObserver::class);

        $this->app->singleton(LoginResponse::class, MyLoginResponse::class);
        $this->app->singleton(LogoutResponse::class, MyLogoutResponse::class);

        FilamentAsset::register([
            Js::make('tiptap-custom-extension-scripts',
            Vite::asset('resources/js/tiptap/extensions.js'))
            ->module()
        ], 'awcodes/tiptap-editor');
    }
}
