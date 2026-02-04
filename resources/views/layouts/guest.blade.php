<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body class="bg-light">
    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="mb-4">
            <a href="/">
                <x-application-logo width="80" style="height: 80px;" />
            </a>
        </div>
        <div class="card shadow-sm w-100" style="max-width: 500px;">
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>