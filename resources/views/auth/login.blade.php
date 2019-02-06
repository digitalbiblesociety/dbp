@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => __('Login')])

<div class="container">
    <div class="column is-8-tablet is-offset-2-tablet">
        <div class="tabs is-centered">
            <ul>
                <li class="is-active"><a href="#">{{ __('Login') }}</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            </ul>
        </div>
        <form method="POST" action="{{ route('login') }}">
            <input name="_token" value="{{ csrf_token() }}" type="hidden" />
            <div class="field">
                <label class="label" for="email">{{ __('E-Mail Address') }}</label>
                <div class="control"><input class="input" id="email" type="text" autocomplete="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email"></div>
                @if($errors->has('email')) <p class="help is-danger">{{ $errors->first('email') }}</p> @endif
            </div>
            <div class="field">
                <label class="label" for="email">{{ __('Password') }}</label>
                <div class="control"><input class="input" id="password" type="password" name="password" required placeholder="Password"></div>
                @if($errors->has('password')) <p class="help is-danger">{{ $errors->first('password') }}</p> @endif
            </div>
            <div class="field">
                <div class="control"><label id="remember" name="remember" class="checkbox"><input type="checkbox" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}</label></div>
                <a href="{{ route('password.request') }}">Forgot Password?</a>
            </div>
            <div class="field is-grouped">
                <div class="control"><button type="submit" class="button is-link">{{ __('Login') }}</button></div>
            </div>
            @include('layouts.partials.socials')
        </form>
    </div>
</div>
@endsection
