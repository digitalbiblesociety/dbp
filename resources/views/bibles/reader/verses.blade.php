@extends('layouts.app')

@section('head')
    <style>
        .text-wrapper {
            width:80%;
            margin:0 auto;
            text-align: justify;
        }
        .text-wrapper b {
            margin-right:10px;
        }
    </style>
@endsection

@section('content')


@include('layouts.partials.banner', [
    'title' => 'Chapter '.$verses->first()->chapter,
    'breadcrumbs' => [
        'Reader' => '#'
    ]
])

    <div class="container box">

        <div class="text-wrapper">
        @foreach($verses as $verse)
            <p><b>{{ $verse->verse_start }}</b>{{ $verse->verse_text }}</p>
        @endforeach
        </div>

    </div>

@endsection