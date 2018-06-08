@extends('layouts.app')

@section('head')
    <style>
        section[role="banner"] {
            background-color:#111;
        }

        section[role="banner"] img {
            display: block;
            margin:0 auto;
        }

        section h1 {
            text-align: center;
            font-size:14px;
        }
    </style>
@endsection

@section('content')

    @if(isset($project->url_banner))
        <section role="banner">
            <img src="/img/projects/{{ $project->url_banner }}" />
        </section>
    @endif

    <section>
        <h1>{{ $project->name }}</h1>
        <p>
            The Bible is project is the keystone app of the API.
        </p>
        <ul>
            <li>{{ $project->id }}</li>
            {{-- <li>{{ $project->url_avatar }}</li> --}}
            {{-- <li>{{ $project->url_avatar_icon }}</li> --}}
            {{-- <li>{{ $project->url_site }}</li> --}}
            <li>{{ $project->description }}</li>
            <li>{{ $project->sensitive }}</li>
        </ul>
    </section>



@endsection