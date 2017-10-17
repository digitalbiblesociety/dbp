@extends('layouts.app')

@section('head')
    <title>{{ trans('titles.401_page_title') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('titles.401_description') }}">

    <style>
        .error-401 h1 {
            font-size:2rem;
            max-width:500px;
            margin:0 auto;
            letter-spacing: 2px;
        }

        .error-401 {

        }


        .error-401-image {
            margin:0 auto;
            display: block;
            width:120px;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'banner_class'  => 'error-401',
        'title'         =>  $message,
        'image'         => '/img/banners/sword.png',
        'image_class'   => 'error-401-image'
    ])

@endsection