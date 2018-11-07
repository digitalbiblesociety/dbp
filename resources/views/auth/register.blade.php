@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', ['title' => __('Register')])

    <div class="container">
        <div class="column is-8-tablet is-offset-2-tablet">
            <div class="tabs is-centered">
                <ul>
                    <li><a href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    <li class="is-active"><a href="#">{{ __('Register') }}</a></li>
                </ul>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf
                <input type="hidden" name="v" value="4">
                <input type="hidden" name="project_id" value="{{ $project->id }}">
                <div class="columns">
                <div class="field column">
                    <label class="label" for="email">{{ __('Username') }}</label>
                    <div class="control"><input class="input" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="username"></div>
                    @if($errors->has('name')) <p class="help is-danger">{{ $errors->first('name') }}</p> @endif
                </div>

                <div class="field column">
                    <label class="label" for="email">{{ __('E-Mail Address') }}</label>
                    <div class="control"><input class="input" type="text" name="email" value="{{ old('email') }}" required placeholder="Email"></div>
                    @if($errors->has('email')) <p class="help is-danger">{{ $errors->first('email') }}</p> @endif
                </div>
                </div>

                <div class="columns">
                <div class="field column">
                    <label class="label" for="email">{{ __('First Name') }}</label>
                    <div class="control"><input class="input" type="text" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name"></div>
                    @if($errors->has('first_name')) <p class="help is-danger">{{ $errors->first('first_name') }}</p> @endif
                </div>

                <div class="field column">
                    <label class="label" for="email">{{ __('Last Name') }}</label>
                    <div class="control"><input class="input" type="text" name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name"></div>
                    @if($errors->has('last_name')) <p class="help is-danger">{{ $errors->first('last_name') }}</p> @endif
                </div>
                </div>
                <div class="columns">
                <div class="field column">
                    <label class="label" for="email">{{ __('Password') }}</label>
                    <div class="control"><input class="input" type="password" name="password" required placeholder="Password"></div>
                    @if($errors->has('password')) <p class="help is-danger">{{ $errors->first('password') }}</p> @endif
                </div>

                <div class="field column">
                    <label class="label" for="email">{{ __('Confirm Password') }}</label>
                    <div class="control"><input class="input" type="password" name="password_confirmation" required placeholder="Password"></div>
                    @if($errors->has('password_confirmation')) <p class="help is-danger">{{ $errors->first('password_confirmation') }}</p> @endif
                </div>
                </div>

                    @if(config('settings.reCaptchStatus'))
                        <div class="form-group">
                            <div class="col-sm-6 col-sm-offset-4">
                                <div class="g-recaptcha" data-sitekey="{{ config('settings.reCaptchSite') }}"></div>
                            </div>
                        </div>
                    @endif

                <button type="submit" class="button is-primary">{{ __('Register') }}</button>
                @include('layouts.partials.socials')

            </form>
        </div>
    </div>
@endsection

@section('footer_scripts')
    @if(config('settings.reCaptchStatus'))
        <script src='https://www.google.com/recaptcha/api.js'></script>
    @endif
@endsection
