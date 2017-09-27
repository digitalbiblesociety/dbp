@extends('layouts.app')

@section('head')
    <title>{{ trans('wiki_numerals.index_title') }}</title>
@endsection

@section('content')

    <section class="banner">
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
                <td>{{ $alphabet->script }}</td>
                <td>{{ $alphabet->name }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection