<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Smart Piket SMPN 25 Makassar | @yield('title')</title>

    <link href="{{ asset('dist/css/tabler.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-flags.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-payments.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/tabler-vendors.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('dist/css/demo.min.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('icon/line-awesome/css/line-awesome.min.css') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Custom Styles -->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />


    <!-- Livewire Styles -->
    @livewireStyles
    <!-- Component Style -->
    @stack('styles')
</head>

<body class="@yield('body-class')">
    <script src="{{ asset('dist/js/demo-theme.min.js') }}"></script>

    @yield('content')

    <!-- Livewire Styles -->
    @livewireScripts

    <script src="{{ asset('dist/js/tabler.js') }}"></script>
    <script src="{{ asset('dist/js/demo.min.js') }}"></script>

    <script src="{{ asset('dist/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/js/jsvectormap.min.js') }}"></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world.js') }}"></script>
    <script src="{{ asset('dist/libs/jsvectormap/dist/maps/world-merc.js') }}"></script>

    <script>
        console.log('DEVELOPED BY RYOOGEN MEDIA 👋');

        var useServerTime = true;
        var serverTime = {{ time() * 1000 }};
        var clientTime = new Date().getTime();
        var differenceTime = clientTime - serverTime;
    </script>

    <script src="{{ asset('js/today.js') }}"></script>
    <script src="{{ asset('js/password-toggle.js') }}"></script>

    @stack('scripts')
</body>
