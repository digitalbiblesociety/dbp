@extends('bibles.reader.simple-reader')

@section('head')
    <style>
        .result {
            font-size: .75rem;
            background: #f1f1f1;
            padding:10px;
            margin:5px;
        }

        .result a {
            color:#222;
        }

        .result a b {
            color:#701;
        }

        .result a:hover {
            color:#000;
            background:#f2f2f2;
        }
    </style>
@endsection

@section('nav')
    <a href="{{ route('ui_bibleDisplay_read.index') }}">Bibles</a>
    <a href="{{ route('ui_bibleDisplay_read.bible', $bible_id) }}">Index</a>
    <a href="#" class="active">Search</a>
@endsection

@section('content')

<form id="search-form" method="post" action="{{ route('ui_bibleDisplay_read.search', $bible_id) }}">
    {{ csrf_field() }}
    <input class="search" type="text" name="search" placeholder="Romanos 10:17 or Jesus">
    <input type="hidden" name="bible_id" id="volume" value="{{ $bible_id }}">
    <button class="search-button" type="submit">Search</button>
</form>

<div class="results">
    @if(isset($query))
        @if($verses->count() == 0)
            <p>No Results Found</p>
        @else
        @foreach($verses as $verse)
            <div class="result">
                <a href="/read/{{ $bible_id }}/{{ $verse->book }}/{{ $verse->chapter }}"><strong>{{ @$verse->book->name ?? $verse->book }} {{ $verse->chapter }}:{{ $verse->verse_start }}</strong><br> {!! str_replace($query,"<b>$query</b>",$verse->verse_text) !!}</a>
            </div>
        @endforeach
            @endif
    @endif
</div>
@endsection