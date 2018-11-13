@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Bibles',
        'breadcrumbs' => [
            route('reader.languages') => 'Reader',
            '#' => 'Bibles'
        ]
    ])

    <div class="container box">

        @foreach($bibles as $bible)
            <a href="{{ route('reader.books',['bible_id' => $bible->id]) }}">{{ $bible->translations->pluck('name')->implode(',') }}</a>
        @endforeach

    </div>

@endsection