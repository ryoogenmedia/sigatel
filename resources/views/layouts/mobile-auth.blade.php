<!DOCTYPE html>
<html lang="en">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Multipurpose, super flexible, powerful, clean modern responsive bootstrap 5 admin template"
        name="description">
    <meta
        content="admin template, axelit admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">
    <meta content="la-themes" name="author">

    <link href="{{ asset('favicon.ico') }}" rel="icon" type="image/x-icon">
    <link href="{{ asset('favicon.ico') }}" rel="shortcut icon" type="image/x-icon">
    <link href="{{ asset('mobile/assets/vendor/fontawesome/css/all.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/ionio-icon/css/iconoir.css') }}" rel="stylesheet">
    <link href="{{ asset('mobile/assets/vendor/tabler-icons/tabler-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('mobile/assets/vendor/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
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

    <style>
        .login-form.container{
            margin: 0px 50px !important;
        }
    </style>

    <title>Smart Piket SMPN 25 Makassar | @yield('title')</title>
</head>

<body>
    <div class="app-wrapper d-block">
        <div class="">
            <main class="w-100 p-0">
                <div class="container">
                    <div class="row">
                        <div class="col-12 p-0">
                            <div class="login-form-container">
                                <div class="mb-4">
                                    <a class="logo d-inline-block" href="index.html">
                                        <img alt="logo-smart-piket" src="{{ asset('ryoogenmedia/logo-dark.png') }}"
                                            width="250">
                                    </a>
                                </div>

                                <div class="form_container">
                                    @if ($errors->any())
                                        <div class="mx-3 mb-1 mt-4">
                                            <div class="alert alert-border-warning" role="alert">
                                                <h6>Ada Yang Salah</h6>

                                                @foreach ($errors->all() as $error)
                                                    <p class="mb-1">- {{ $error }}.</p>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    {{ $slot }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts

    <script src="{{ asset('mobile/assets/js/jquery-3.6.3.min.js') }}"></script>
    <script src="{{ asset('mobile/assets/vendor/bootstrap/bootstrap.bundle.min.js') }}"></script>

    @yield('scripts')
</body>

</html>
