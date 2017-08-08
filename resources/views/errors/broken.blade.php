@extends('layouts.app')

@section('head')
    <title>{{ trans('titles.404_page_title') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('titles.404_description') }}">

    <style>
        body {
            background-image: url("/img/errors/{{ $status }}.jpg")!important;
            background-repeat: no-repeat;
            background-size: cover;
        }
        header {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        h1.error-message {
            color:#FFF;
            background: rgba(0,0,0,.5);
            padding:40px;
            margin:25px;
        }
    </style>
@endsection

@section('content')
    <header>
        <h1 class="error-message text-center">{{ $message }}</h1>
    </header>
@endsection