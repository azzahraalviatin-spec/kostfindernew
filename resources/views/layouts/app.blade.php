<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>KostFinder - @yield('title', 'Platform Kost Jawa Timur')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        :root { --bs-primary: #E8401C; }
        .btn-primary { background-color: #E8401C; border-color: #E8401C; }
        .btn-primary:hover { background-color: #c0300e; border-color: #c0300e; }
        .text-primary { color: #E8401C !important; }
    </style>

    @yield('styles')
</head>
<body>

    @include('layouts.navigation')

    @yield('content')

    @include('layouts.footer')

    @yield('scripts')
    @include('layouts._bottom-nav')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>