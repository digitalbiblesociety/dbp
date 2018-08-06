<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- CSRF Token --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@if (trim($__env->yieldContent('template_title')))@yield('template_title') | @endif {{ config('app.name', Lang::get('titles.app')) }}</title>
    <meta name="description" content="">
    <meta name="author" content="Digital Bible Society">
    <link rel="shortcut icon" href="/favicon.ico">

    {{-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries --}}
        <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- Fonts --}}
    @yield('template_linked_fonts')

    {{-- Styles --}}
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">

    @yield('template_linked_css')

    <style type="text/css">
        @yield('template_fastload_css')

            @if (Auth::User() && (Auth::User()->profile) && (Auth::User()->profile->avatar_status == 0))
                .user-avatar-nav {
            background: url({{ Gravatar::get(Auth::user()->email) }}) 50% 50% no-repeat;
            background-size: auto 100%;
        }
        @endif

    </style>

    {{-- Scripts --}}
    <script>
		window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
    </script>

    @yield('head')

</head>
<body>
<div>

    <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                {!! config('app.name', trans('titles.app')) !!}
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                <span class="sr-only">{!! trans('titles.toggleNav') !!}</span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                {{-- Left Side Of Navbar --}}
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/" id="navbarDropdown">
                            << Back
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <main class="py-4">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    @include('layouts.partials.form-status')
                </div>
            </div>
        </div>

        @yield('content')

    </main>

</div>

{{-- Scripts --}}
@if(config('settings.googleMapsAPIStatus'))
    {!! HTML::script('//maps.googleapis.com/maps/api/js?key='.config("settings.googleMapsAPIKey").'&libraries=places&dummy=.js', array('type' => 'text/javascript')) !!}
@endif

@yield('footer_scripts')

</body>
</html>
