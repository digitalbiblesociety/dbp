@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => __('Reset Password')
    ])

    <div class="container">
        @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div> @endif

        <div class="columns">
            <form class="column is-half is-offset-one-quarter" method="POST" action="/password/reset/action">
                @csrf
                <div class="box">

                    @if ($errors)
                        <div class="alert alert-error has-text-centered">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif

                    <input type="hidden" name="token_id" value="{{ $token }}">
                    <div class="field">
                        <label class="label" for="email">{{ __('E-Mail Address') }}</label>
                        <div class="control"><input class="input is-medium" name="email" type="email" autocomplete="email" value="{{ $email ?? old('email') }}"></div>
                        @if($errors->has('email')) <p class="help is-danger">{{ $errors->first('email') }}</p> @endif
                    </div>

                    <div class="field">
                        <label class="label" for="new_password">{{ __('New Password') }}</label>
                        <div class="control"><input class="input is-medium" id="password" type="password" name="new_password" required></div>
                        @if($errors->has('password')) <span class="help is-danger"><strong>{{ $errors->first('password') }}</strong></span> @endif
                    </div>

                    <div class="field">
                        <label class="label" for="password-confirm">{{ __('Confirm Password') }}</label>
                        <div class="control"><input class="input is-medium" id="password-confirm" type="password" name="new_password_confirmation" required></div>
                        @if($errors->has('new_password_confirmation'))<span class="help is-danger"><strong>{{ $errors->first('new_password_confirmation') }}</strong></span>@endif
                    </div>

                    <button type="submit" class="button">{{ __('Reset Password') }}</button>
                </div>
            </form>
        </div>

    </div>

@endsection
