@extends('layouts.app')

@section('head')
    <title>{{ trans('titles.broken_page_title') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('titles.broken_description') }}">
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title'         =>  $message,
    ])

@endsection