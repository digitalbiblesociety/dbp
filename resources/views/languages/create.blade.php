@extends('layouts.app')

@section('content')
    <div class="row">
        <h1 class="text-center">Language Create</h1>
        <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#fields" aria-selected="true">Single Creation</a></li>
            <li class="tabs-title"><a data-tabs-target="field_descriptions" href="#field_descriptions">Field Descriptions</a></li>
        </ul>
    </div>

    <form action="/languages" method="POST">
        {{ csrf_field() }}
        @include('languages.form')
        <div class="medium-4 columns centered">
            <input type="submit" class="button">
        </div>
    </form>

@endsection

@section('footer')

@endsection