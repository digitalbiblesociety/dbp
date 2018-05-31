@extends('layouts.app')

@section('head')

@endsection

@section('content')

    <div role="banner">
        <h1 itemprop="name" class="text-center">{{ $language->name }}</h1>
        <h2 itemprop="alternateName" class="text-center">{{ $language->autonym }}</h2>
    </div>

    <form action="language" method="POST">
        {{ csrf_field() }}
        @include('languages.form')
        <div class="medium-4 columns centered">
            <input type="submit" class="button">
        </div>
    </form>

@endsection