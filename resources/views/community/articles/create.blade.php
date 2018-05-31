@extends('layouts.app')

@section('content')

    <form class="row" action="{{ route('view_articles.store') }}" method="post">
        @include('articles.form')
    </form>

@endsection