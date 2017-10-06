@extends('layouts.app')

@section('content')

    <form action="/numbers/{{ $system }}" method="POST" class="row">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="_method" value="PUT">
        @include('languages.alphabets.numerals.form')
    </form>

@endsection