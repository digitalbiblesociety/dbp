@extends('bibles.reader.simple-reader')

@section('nav')
    @if(in_array(($verses->first()->chapter - 1), $bibleNavigation[$verses->first()->book]->pluck('chapter')->ToArray())) <a href="/read/{{ $bible_id }}/{{ $verses->first()->book }}/{{ $verses->first()->chapter - 1 }}" class="sideNav left"><<</a> @endif
    <a href="{{ route('ui_bibleDisplay_read.index') }}">Bibles</a>
    <a href="{{ route('ui_bibleDisplay_read.bible', $bible_id) }}">Index</a>
    <a href="{{ route('ui_bibleDisplay_read.search', $bible_id) }}">Search</a>
    @if(in_array(($verses->first()->chapter + 1), $bibleNavigation[$verses->first()->book]->pluck('chapter')->ToArray())) <a href="/read/{{ $bible_id }}/{{ $verses->first()->book }}/{{ $verses->first()->chapter + 1 }}" class="sideNav right"> >> </a> @endif
@endsection

@section('content')
            {{-- <h2>{{ @$verses->first()->book->currentTranslation->name ?? $verses->first()->book }} {{ $verses->first()->chapter }}</h2> --}}
        @foreach($verses as $verse)
           <sup>{{ $verse->verse_start }}@if(isset($verse->verse_end))-{{ $verse->verse_end }}@endif</sup> {{ $verse->verse_text }}
        @endforeach
@endsection