<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'URL Shortner') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">

    <!-- Styles -->
    <link href="{{ asset('assets/css/bs5.min.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        
        @include('layouts.navbar')

        @auth
            @include('layouts.sidebar')
        @else
            <main class="py-0">
                @yield('content')
            </main>
        @endauth
        
        {{-- <main class="py-0">
            @yield('content')
        </main> --}}

        {{-- @include('layouts.footer') --}}
    </div>

    <!-- Scripts -->
    <script src="{{ asset('assets/js/jquery-3.6.2.min.js') }}"></script>
    <script src="{{ asset('assets/js/bs5.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>

</body>
</html>
