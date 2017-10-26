@extends('layouts.app')

@section('head')
    <title>{{ trans('titles.404_page_title') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('titles.404_description') }}">
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'banner_class'  => 'error-404',
        'title'         => 'Page not Found'
    ])

@endsection