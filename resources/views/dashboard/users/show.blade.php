@extends('layouts.app')

@section('head')
    <style>

        header {

        }

        header img {

        }

    </style>
@endsection

@section('content')

<header>
    <h1>{{ $user->name }} <small>{{ $user->id }}</small></h1>
    <a class="button" href="/dashboard/users/{{ $user->id }}/edit">Edit</a>
    <img src="/storage/img/{{ $user->id }}.jpeg" />
</header>

@endsection