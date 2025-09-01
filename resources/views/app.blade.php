<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="facebook-domain-verification" content="ya9dx7u6o4lf2mne0n9tkk8yau8jph" />
        <title inertia>{{ config('app.name', 'sickofmetal') }}</title>
        <link rel="canonical" href="{{ request()->url() }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        {{-- FAVICON --}}
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
        @filamentStyles
        {{-- @routes --}}
        @vite(['resources/js/app.ts', 'resources/styles/main.scss'])
        @inertiaHead
    </head>
    <body>
        @routes
        @filamentScripts
        @inertia
    </body>
</html>
