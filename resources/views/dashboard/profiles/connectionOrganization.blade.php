@extends('layouts.app')

@section('')

@section('content')

    @include('layouts.partials.banner', ['title' => 'User Organizations'])

    @if(isset($user->organizations))
    @foreach($user->organizations as $organization)
        <small>{{ $organization->id }}</small>
    @endforeach
    @else
        <p>No Organization Connections Exist</p>
    @endif

@endsection