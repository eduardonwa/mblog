<?php

namespace App\Filament;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Filament\Http\Responses\Auth\Contracts\LoginResponse as LoginResponseContract;

class MyLoginResponse implements LoginResponseContract
{
    public function toResponse($request): RedirectResponse
    {
        return redirect()->intended(Filament::getUrl());
    }
}