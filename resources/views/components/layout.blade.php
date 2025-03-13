<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{ $meta ?? '' }}
    @vite(['resources/css/app.css'])
    <title>Document</title>
</head>
<body>
    {{ $slot }}
</body>
</html>