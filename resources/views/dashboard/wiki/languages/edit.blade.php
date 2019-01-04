@extends('layouts.app')

@section('head')

@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title'       => trans('dashboard.languages_create_title'),
        'breadcrumbs' => [
            '/'                          => trans('dashboard.'),
            route('dashboard.languages') => trans('dashboard.languages'),
            '#'                          => trans('dashboard.languages_edit_title')
        ]
    ])

    <form action="{{ route('dashboard.languages.store') }}" method="POST">
        {{ method_field('PUT') }}
        @include('dashboard.wiki.languages.form')
    </form>


@endsection