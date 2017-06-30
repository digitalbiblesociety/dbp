@extends('layouts.app')

@section('head')
    <style>
        label,
        input {
            display: block;
            width:100%;
        }

    </style>
@endsection

@section('content')

            <div class="row">
            <form role="form" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="row">
                <div class="medium-6 columns"><label>E-mail Address</label><input id="email" type="email" name="email" value="{{ old('email') }}" required></div>
                <div class="medium-6 columns"><label>Password</label><input id="password" type="password" name="password" required></div>
                </div>
                <div class="medium-6 columns"><button type="submit" class="button">Login</button></div>
            </form>
            </div>

@endsection
