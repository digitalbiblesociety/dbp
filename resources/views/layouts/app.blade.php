<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />


    <meta property="og:site_name" content="{{ trans('app.site_name') }}" />

    @if(env('APP_DEBUG') == 'true')
        <link rel="shortcut icon" href="/favicon_test.ico" type="image/x-icon">
        <link rel="icon" href="/favicon_test.ico" type="image/x-icon">
    @else
        <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
    @endif

    @if(Localization::isLocalizedRoute())
        @foreach(Localization::getLocales() as $localeCode => $properties)

            @if(Route::current()->getLocalization() == $localeCode)
                <meta property="og:locale" content="{{ $localeCode }}" />
            @else
                <meta property="og:locale:alternate" content="{{ $localeCode }}" />
                <link rel="alternate" hreflang="{{ $localeCode }}" href="{{ Localization::getLocaleUrl($localeCode) }}">
            @endif

        @endforeach
    @endif

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head')
    <style>
        #translation-dropdown .navbar-link {
            text-align: center;
            font-size: 20px;
            margin:0 auto;
            display: block;
            padding: 10px 20px;
        }

        #translation-dropdown .navbar-link:after {
            display: none;
        }
    </style>
    <script>
        var App = {
        	apiParams: {
        		'key': '{{ config('services.bibleIs.key') }}',
                'v': '4',
        	}
        };
    </script>
</head>
<body>
@include('layouts.partials.nav')

<main id="app">
@yield('content')
</main>

<script src="{{ mix('js/app.js') }}"></script>
<script src="{{ mix('js/bulma.js') }}"></script>
@yield('footer')
</body>
</html>
