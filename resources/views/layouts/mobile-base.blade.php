<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Multipurpose, super Feb, powerful, clean modern responsive bootstrap 5 admin template"
        name="description">
    <meta
        content="admin template, axelit admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">
    <meta content="la-themes" name="author">

    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@$ICONS_VERSION/dist/tabler-icons.min.css">

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('mobile/assets/vendor/fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/ionio-icon/css/iconoir.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/animation/animate.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/tabler-icons-3.31.0/webfont/tabler-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/vendor/flag-icons-master/flag-icon.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/vendor/apexcharts/apexcharts.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/vendor/simplebar/simplebar.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/vendor/slick/slick.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/slick/slick-theme.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/filepond/filepond.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/filepond/image-preview.min.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/css/responsive.css') }}" rel="stylesheet" type="text/css">

    <link href="https://fonts.googleapis.com/" rel="preconnect">
    <link crossorigin href="https://fonts.gstatic.com/" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&amp;display=swap"
        rel="stylesheet">

    <!-- Livewire Styles -->
    @livewireStyles

    <!-- Component Style -->
    @yield('styles')

    <title>Smart Piket | {{ $title }}</title>


</head>

<body>
    <div class="app-wrapper">
        <x-mobile.loader />

        <x-mobile.backend.sidebar />

        <div class="app-content">
            <div class="">

                <x-mobile.backend.header />

                <main>
                    {{ $slot }}
                </main>

            </div>
        </div>

        <x-mobile.backend.goto-top />

        <x-mobile.backend.footer />
    </div>

    @livewireScripts

    <!-- latest jquery-->
    <script src="{{ asset('mobile/assets/js/jquery-3.6.3.min.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('mobile/assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    <!-- Simple bar js-->
    <script src="{{ asset('mobile/assets/vendor/simplebar/simplebar.js') }}"></script>

    <!-- apexcharts -->
    <script src="{{ asset('mobile/assets/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/apexcharts/column/dayjs.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/apexcharts/column/quarterOfYear.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/apexcharts/timelinechart/moment.min.js') }}"></script>

    <!-- Customizer js-->
    <script src="{{ asset('mobile/assets/js/customizer.js') }}"></script>

    <!-- phosphor js -->
    <script src="{{ asset('mobile/assets/vendor/phosphor/phosphor.js') }}"></script>

    <!-- slick-file -->
    <script src="{{ asset('mobile/assets/vendor/slick/slick.min.js') }}"></script>

    <!-- filepond -->
    <script src="{{ asset('mobile/assets/vendor/filepond/file-encode.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/filepond/validate-size.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/filepond/validate-type.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/filepond/exif-orientation.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/filepond/image-preview.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/filepond/filepond.min.js') }}"></script>

    <!-- Project Dashboard js-->
    <script src="{{ asset('mobile/assets/js/project_dashboard.js') }}"></script>

    <!-- App js-->
    <script src="{{ asset('mobile/assets/js/script.js') }}"></script>

    @yield('scripts')
</body>

</html>
