@extends('layouts.app')

@section('content')
    <h1 class="text-center">Language Edit</h1>

    <form action="language" method="POST">
        {{ csrf_field() }}
        @include('languages.form')
        <div class="medium-4 columns centered">
            <input type="submit" class="button">
        </div>
    </form>

@endsection