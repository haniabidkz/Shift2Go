<!doctype html>
@php

$logo=\App\Models\Utility::get_file('uploads/logo/');
$company_favicon = Utility::getValByName('company_favicon');
$SITE_RTL = Utility::getValByName('SITE_RTL');
$setting = App\Models\Utility::colorset();
$setting = \App\Models\Utility::settings();
$color = 'theme-3';
if (!empty($setting['color'])) {
    $color = $setting['color'];
}
$darklayout = Utility::getValByName('cust_darklayout');
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $SITE_RTL == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        {{ Utility::getValByName('title_text')? Utility::getValByName('title_text'): config('app.name', 'RotaGo SaaS') }}
        - @yield('page-title') </title>
        <link rel="icon" href="{{ $logo . (isset($favicon) && !empty($favicon) ? $favicon : 'favicon.png') }}"
        type="image/x-icon" />
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <!-- <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link"> -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">

  @if ($SITE_RTL == 'on')
  <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
  @endif

    @if ($darklayout == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @endif

    <style>
        [dir="rtl"] .dash-sidebar {
            left: auto !important;
        }

        /* [dir="rtl"] .dash-header {
            left: 0;
            right: 280px;
        } */

        [dir="rtl"] .dash-header:not(.transprent-bg) .header-wrapper {
            padding: 0 0 0 30px;
        }

        [dir="rtl"] .dash-header:not(.transprent-bg):not(.dash-mob-header) ~ .dash-container {
            margin-left: 0px;
        }

        [dir="rtl"] .me-auto.dash-mob-drp {
            margin-right: 10px !important;
        }

        [dir="rtl"] .me-auto {
            margin-left: 10px !important;
        }
    </style>
    <style>
        .language_option_bg option {
            background-color: #fff;
            color: #000;
        }
    </style>
</head>

<body class="{{ $color }}">
    <!-- [ auth-signup ] start -->
    <div class="auth-wrapper auth-v3">
        <div class="bg-auth-side bg-primary"></div>
        <div class="auth-content">
            <nav class="navbar navbar-expand-md navbar-light default">
                <div class="container-fluid pe-2">
                    <a class="navbar-brand" href="#">
                        @if (!empty($darklayout) && $darklayout == 'on')
                            <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo-light.png')}}"
                            class="logo logo-lg" alt="..." >
                        @else
                            <img src="{{$logo.(isset($company_logos) && !empty($company_logo)?$company_logo:'logo-dark.png')}}"
                            class="logo logo-lg" alt="..." >
                        @endif
                    </a>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false"
                            aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                        </button>

                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                        <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">{{ __('Support') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ __('Terms') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">{{ __('Privacy') }}</a>
                            </li>
                            <li class="nav-item">
								@yield('lang-selectbox')
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
			@yield('content')
            <div class="auth-footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6">
                            <p class="text-body">{{__('Copyright')}} {{ (Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :config('app.name', 'RotaGo') }} {{date('Y')}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ auth-signup ] end -->

    <!-- Scripts -->
    <script src="{{ asset('custom/libs/jquery/dist/jquery.min.js')}}"></script>
    <script src="{{ asset('assets/js/vendor-all.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
    <script>
        feather.replace();
    </script>

    @stack('custom-scripts')
    @if ($setting['cookie_consent'] == 'on')
        @include('layouts.cookie_consent')
    @endif
    @stack('pagescript')

    @if (\Session::has('success'))
        <script>
            show_toastr('{{ __('Success') }}', '{!! session('success') !!}', 'success');
        </script>
        {{ Session::forget('success') }}
    @endif

    @if (Session::has('error'))
        <script>
            show_toastr('{{ __('Error') }}', '{!! session('error') !!}', 'error');
        </script>
        {{ Session::forget('error') }}
    @endif

</body>

</html>
