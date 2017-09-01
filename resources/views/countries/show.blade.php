@extends('layouts.app')

@section('content')

    <h1>{{ $country->name }}</h1>
    <small>{{ $country->id }}</small>

@endsection