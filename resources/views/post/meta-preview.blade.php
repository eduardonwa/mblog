<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <title>{{ $meta['title'] }}</title>
        <meta name="description" content="{{ $meta['description'] }}">
        <meta name="author" content="{{ $meta['author'] }}">
        <meta property="fb:app_id" content="1156351909629945" />

        <!-- Open Graph -->
        <meta property="og:title" content="{{ $meta['title'] }}">
        <meta property="og:description" content="{{ $meta['description'] }}">
        <meta property="og:image" content="{{ $meta['thumbnail'] }}">
        <meta property="og:url" content="{{ $meta['url'] }}">
        <meta property="og:type" content="article">
        <meta property="og:type" content="{{ $meta['type'] ?? 'website' }}">
        <meta property="og:locale" content="es_MX">

        @if($meta['type'] === 'profile' && isset($user))
            <meta property="profile:username" content="{{ $user->username }}">
        @endif

        <!-- Twitter -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $meta['title'] }}">
        <meta name="twitter:description" content="{{ $meta['description'] }}">
        <meta name="twitter:image" content="{{ $meta['thumbnail'] }}">
    </head>
</html>
