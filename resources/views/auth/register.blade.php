@extends('layouts.app')

@section('content')


    <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
        {{ csrf_field() }}
        <label>Name <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus></label>
        <label>E-Mail Address <input id="email" type="email" name="email" value="{{ old('email') }}" required></label>
        <label>Password <input id="password" type="password" name="password" required></label>
        <label>Confirm Password <input id="password-confirm" type="password" name="password_confirmation" required></label>
        <button type="submit" class="button">Register</button>
    </form>

@endsection
