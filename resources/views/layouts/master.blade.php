<!doctype html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @yield('header')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ asset('semantic/dist/semantic.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-slim.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>
    @yield('content')

    @yield('footer')

    <script
    src="https://code.jquery.com/jquery-3.3.1.min.js"
    integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
    crossorigin="anonymous"></script>

    <script src="{{ asset('semantic/dist/semantic.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/app.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/script.js') }}" charset="utf-8"></script>

    @stack('scripts')
</body>
</html>
