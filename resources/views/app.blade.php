<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="facebook-domain-verification" content="ya9dx7u6o4lf2mne0n9tkk8yau8jph" />
        <title inertia>{{ config('app.name', 'sickofmetal') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @filamentStyles
        {{-- @routes --}}
        @vite(['resources/js/app.ts', 'resources/styles/main.scss', 'resources/js/tiptap/extensions.js'])
        @inertiaHead
    </head>
    <body>
        @routes
        @filamentScripts
        @inertia
    </body>
</html>
