@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title'     => 'Create a new Bible',
        'subtitle'  => ''
    ])

    <div class="container">
        @include('dashboard.bibles.form', ['type' => 'POST'])
    </div>


@endsection