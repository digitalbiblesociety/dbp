@extends('layouts.app')

@section('head')
    <style>html, body {height: 100%}

    .menu-list a {
        display: block;
        text-align: center;
        font-size: smaller;
    }

    </style>
@endsection

@section('footer')
    <script src="{{ mix('/js/docs.js') }}"></script>
@endsection