@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner',
        [
            'title'       => __('dashboard.projects.edit.title'),
            'breadcrumbs' => [
                route('dashboard')                => __('dashboard.home.title'),
                route('dashboard.projects.index') => __('dashboard.projects.index.title'),
                '#'                               => __('dashboard.projects.edit.title')
            ]
        ]
    )

    <form class="container" action="{{ route('dashboard.projects.update', ['id' => $project->id]) }}" method="POST">
        @include('dashboard.projects.form', ['project' => $project])
    </form>

@endsection