<!DOCTYPE html>
<html class="no-js" lang="en" xmlns:fb="http://ogp.me/ns/fb#">
<head>
    <meta name="viewport" content="user-scalable=no, initial-scale=1.0, minimum-scale=1, maximum-scale=1">
    <meta charset="utf-8">
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="Bible.is" />
    <meta name="author" content="Faith Comes By Hearing"/>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @if(env('ITUNES_APP_ID'))
        <meta name="apple-itunes-app" content="app-id={{ env("ITUNES_APP_ID") }}" />
    @endif
    <meta name="google-play-app" content="app-id=com.faithcomesbyhearing.android.bibleis">
    <link rel="favorite icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="apple-touch-icon" href="/images/icons/apple-touch-icon-152.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/images/icons/apple-touch-icon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="t/images/icons/apple-touch-icon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/images/icons/apple-touch-icon-152.png">
    <!--[if IE 8]> <link href="/css/ie8.css?cr=1" rel="stylesheet" type="text/css" media="screen" /> -->

    <style>
        #nav {
            margin: 0 auto;
            max-width:800px;
            text-align: center;
        }
        #nav a {
            font-size: 1.25rem;
            text-decoration: none;
            padding:20px;
            color:#222;
        }
        #nav a.active {
            color:#888;
        }
        .reader {
            text-align:justify;
            line-height:1.5;
            font-size:1.5rem;
            padding-top:70px;
            max-width:800px;
            margin:0 auto;
        }
    </style>
    @yield('head')
</head>
<body>
<div id="nav">
    @yield('nav')
</div>
<div class="reader">
    @yield('content')
</div>
</body>
</html>