<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Sick of Metal')</title>
    @vite(['resources/js/app.ts', 'resources/styles/main.scss'])
</head>
<body>
    @yield('content')
</body>
</html>