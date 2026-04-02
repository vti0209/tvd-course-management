<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hệ thống Quản lý Nhóm 2')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body>

    @include('layouts.header')

    <main class="container main-content">
        @yield('content')
    </main>

    @include('layouts.footer')

    <script src="{{ asset('js/app.js') }}"></script>

    @stack('scripts')

</body>
</html>
