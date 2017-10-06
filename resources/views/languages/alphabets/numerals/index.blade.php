@extends('layouts.app')

@section('head')
    <title>{{ trans('wiki_numerals.index_title') }}</title>
@endsection

@section('content')

    <section class="banner">
        <nav aria-label="You are here:" role="navigation">
            <ul class="breadcrumbs">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('view_alphabets.index') }}">{{ trans('wiki_alphabets.index_title') }}</a></li>
                <li class="disabled">{{ trans('wiki_numbers.index_title') }}</li>
            </ul>
        </nav>
        <h2 class="text-center">{{ trans('wiki_numerals.index_title') }}</h2>
    </section>

    <table class="row">
        <thead>
            <tr>
                <td>{{ trans('wiki_numerals.fields_script') }}</td>
                <td>{{ trans('wiki_numerals.fields_name') }}</td>
            </tr>
        </thead>
        <tbody>
        @foreach($alphabets as $alphabet)
            <tr>
                <td><a href="{{ route('view_numbers.show', $alphabet->script, false) }}">{{ $alphabet->script }}</td>
                <td>{{ $alphabet->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection