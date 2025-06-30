<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    public function __invoke(Request $request): SymfonyResponse|Response
    {
        return $request->user()->hasVerifiedEmail()
            ? Inertia::location('/member')
            : Inertia::render('auth/VerifyEmail', ['status' => $request->session()->get('status')]);
    }
}
