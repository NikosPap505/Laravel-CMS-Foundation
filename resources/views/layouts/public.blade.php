<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') - {{ config('app.name', 'Laravel') }}</title>
</head>
<body class="antialiased">
    <div style="max-width: 800px; margin: 40px auto; padding: 20px; font-family: sans-serif;">
        @yield('content')
    </div>
</body>
</html>