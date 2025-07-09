<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use BeyondCode\Comments\Comment;
use App\Filament\MyLoginResponse;
use App\Filament\MyLogoutResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
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

        $this->app->singleton(LoginResponse::class, MyLoginResponse::class);
        $this->app->singleton(LogoutResponse::class, MyLogoutResponse::class);
    }
}
