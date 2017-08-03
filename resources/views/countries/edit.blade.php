@extends('layouts.app')

@section('content')

    <form method="POST" action="countries">
        {{ csrf_field() }}
        @include('countries.form')
    </form>

@endsection