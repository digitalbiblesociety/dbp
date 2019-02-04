@extends('layouts.app')

@section('head')
    <style>
        .file-name:empty {
            display: none;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner',
        [
            'title'       => __('dashboard.projects.create.title'),
            'breadcrumbs' => [
                route('dashboard')                => __('dashboard.home.title'),
                route('dashboard.projects.index') => __('dashboard.projects.index.title'),
                '#'                               => __('dashboard.projects.create.title')
            ]
        ]
    )

    <form class="container" action="{{ route('dashboard.projects.store') }}" method="POST">
        @include('dashboard.projects.form')
    </form>

@endsection