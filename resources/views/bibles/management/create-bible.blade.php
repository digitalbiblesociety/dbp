@extends('layouts.app')

@section('head')
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => trans('dashboard.bibles_create_title')
    ])

    <div class="container">
        @include('bibles.management.form', ['type' => 'POST'])
    </div>

@endsection