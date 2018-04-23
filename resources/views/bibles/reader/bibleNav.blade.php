@extends('bibles.reader.simple-reader')

@section('nav')
        <a href="{{ route('ui_bibleDisplay_read.index') }}">Bibles</a>
        <a href="#" class="active">Index</a>
        <a href="{{ route('ui_bibleDisplay_read.search', $bible_id) }}">Search</a>
@endsection

@section('content')
    <table>
        @foreach($bibleNavigation as $bookID => $chapters)
            <tr>
            <td class="book">{{ $chapters->first()->book }}</td>
            <td class="chapters @if($loop->first) active @endif">
                @foreach($chapters as $chapter)
                    <a href="/read/{{ $bible_id }}/{{ $chapter->book }}/{{ $chapter->chapter }}">{{ $chapter->chapter }}</a>
                @endforeach
            </td>
            </tr>
        @endforeach
    </table>
@endsection