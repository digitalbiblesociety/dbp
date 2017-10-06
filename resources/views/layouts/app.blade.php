<!DOCTYPE html>
<html class="no-js" lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,900" rel="stylesheet">
    <link rel="stylesheet" href="{{ mix('css/app.css') }}" />

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        footer {
            background: #f8f8f8;
            border-top:thin solid #ccc;
        }

        footer svg {
            width:300px;
            margin:0 auto;
            display: block;
        }
    </style>
    @yield('head')
</head>
<body>

@include('layouts.nav')

<main>
@yield('content')
</main>

    <script src="/js/main.js"></script>
    <script src="/js/foundation.min.js"></script>
    <script>
        $(document).foundation();
    </script>
@yield('footer')
</body>
</html>
