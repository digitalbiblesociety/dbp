@extends('layouts.simple')

@section('head')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>html, body {height: 100%}

    .openapi {
        width: 95%;
        height: 100%;
        margin: 0 auto;
    }
    </style>
@endsection

@section('content')
    <div class="row">
        <div id="app"></div>
    </div>
@endsection

@section('footer_scripts')
    <script src="/js/swagger-vue-v4.js"></script>
@endsection