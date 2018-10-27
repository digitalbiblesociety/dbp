@extends('layouts.app')

@section('head')

@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title'       => trans('dashboard.languages_create_title'),
        'breadcrumbs' => [
            '/'                          => trans('dashboard.'),
            route('dashboard.languages') => trans('dashboard.languages'),
            '#'                          => trans('dashboard.languages_create_title')
        ]
    ])

    <form action="{{ route('dashboard.languages.store') }}" method="POST">
        @include('dashboard.wiki.languages.form')
    </form>


@endsection