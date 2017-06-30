<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('head')
    <title>{{ config('app.name', 'DBP') }}</title>
    <link href="/css/app.css" rel="stylesheet">
</head>
<body>
<aside>
    <ul>
        <li>Digital Bible Platform</li>
            <a href="/bibles">Bibles</a>
            <ul class="menu vertical">
                <li><a href="/bibles/">Bibles</a></li>
                <li><a href="/books/">Books</a></li>
                @if(Auth::user())
                <li><a href="/bibles/create">Add a New Bible</a></li>
                @endif
            </ul>
        </li>
        <li><a href="/languages">Languages</a></li>
        <li><a href="/countries">Countries</a></li>
        @if(!Auth::user())
            <li><a href="/login">Login or Signup</a></li>
        @else
            <li><a href="/home">Home</a></li>
            <li><a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">{{ csrf_field() }}</form>
        @endif
    </ul>
</aside>
<main>
@yield('content')
</main>
@yield('footer')
</body>
</html>
