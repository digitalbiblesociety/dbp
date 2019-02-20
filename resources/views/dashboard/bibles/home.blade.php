@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title'     => 'Bible Metadata',
        'subtitle'  => ''
    ])

    <div class="columns is-centered has-text-centered">
    <section class="is-3 column">
        <label>Search
        <input class="input"></label>
    </section>

    <section class="is-3 column">
        <label>Or...<Br>
        <a class="button is-primary">Create a New Bible</a></label>
    </section>
    </div>

@endsection