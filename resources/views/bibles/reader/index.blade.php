@extends('bibles.reader.simple-reader')

@section('nav')
    <nav class="list">
    @foreach($filesets as $fileset)
        <a href="/read/{{ $fileset->id }}">{!! $fileset->bible->first()->translations->pluck('name')->implode(' | ') !!}</a><br>
    @endforeach
    </nav>
@endsection