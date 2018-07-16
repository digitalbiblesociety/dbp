@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>html, body {height: 100%}</style>
@endsection

@section('content')
    <div class="row">
        <div id="app"></div>
    </div>
@endsection

@section('footer')
    <script src="/js/swagger-vue-v4.js"></script>
@endsection