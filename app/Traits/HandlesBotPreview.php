<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait HandlesBotPreview
{
    protected function isBot(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');

        return preg_match(
            '/facebookexternalhit|facebot|twitterbot|slackbot|linkedinbot|discordbot|telegrambot|googlebot/i',
            $userAgent
        );
    }

    protected function respondWithBotPreview(array $meta, array $payload)
    {
        return response()
            ->view('post.meta-preview', array_merge(['meta' => $meta], $payload))
            ->withoutCookie('sickofmetal_session')
            ->withoutCookie('XSRF-TOKEN')
            ->withHeaders([
                'Cache-Control' => 'public, max-age=3600',
                'X-Frame-Options' => 'ALLOW-FROM https://www.facebook.com'
            ]);
    }
}
