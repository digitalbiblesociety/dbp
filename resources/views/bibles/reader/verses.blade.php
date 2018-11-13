@extends('layouts.app')

@section('content')


@include('layouts.partials.banner', [
    'title' => 'Chapter '.$verses->first()->chapter,
    'breadcrumbs' => [
        'Reader' => '#'
    ]
])

    <div class="container box">

        <h3>{{ $verses->first()->chapter }}</h3>

        @foreach($verses as $verse)
            <p><b>{{ $verse->verse_start }}</b>{{ $verse->verse_text }}</p>
        @endforeach

    </div>

@endsection