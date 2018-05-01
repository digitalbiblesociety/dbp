@extends('layouts.app')

@section('head')
    <style>
        .auth-option-wrap {
            display: flex;
            height:100%;
        }
        .auth-option {
            flex:1;
            transition: all 150ms ease-in-out;
        }
        .auth-option:hover {
            flex-grow: 1.2;
        }
        .auth-option img {
            width: 100px;
            margin:0 auto;
            display: block;
            fill: white;
        }

        .auth-option-action {

        }

        .sign-in {}
        .sign-up {}

        .social-auth {
            background-color:#222;
            font-size:2rem;
            text-align: center;
        }
        .social-auth .icon {
            color:#FFF;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => "",
        'tabs' => [
            'login-tab'    => 'Login',
            'register-tab' => 'Sign Up'
		]
    ])
    <nav class="social-auth">
        <a href="{{ route('login.social_redirect', ['provider' => 'google']) }}"><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#google"></use></svg></a>
        <a href="{{ route('login.social_redirect', ['provider' => 'facebook']) }}"><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#facebook"></use></svg></a>
        <a href="{{ route('login.social_redirect', ['provider' => 'twitter']) }}"><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#twitter"></use></svg></a>
        <a href="{{ route('login.social_redirect', ['provider' => 'github']) }}" href=""><svg class="icon"><use xlink:href="/img/icons/icons-social.svg#github"></use></svg></a>
    </nav>
    <section role="tabpanel" aria-hidden="false" id="login-tab">
        @if($errors->any())
            <div class="medium-6 columns centered">
                <div data-abide-error class="alert callout">@foreach($errors->all() as $error) <p>{{ $error }}</p> @endforeach</div>
            </div>
        @endif
        <form role="form" method="POST" action="{{ route('login') }}" class="medium-6 columns centered">
            {{ csrf_field() }}
            <h3 class="text-center">Sign in</h3>
            <input class="login-box-input" type="text" name="email" placeholder="Username" value="{{ old('email') }}" autocomplete="username" required />
            <input class="login-box-input" type="password" name="password" placeholder="Password" autocomplete="current-password" required />
            <input class="login-box-submit-button" type="submit" name="signup_submit" value="Sign In" />
            <a href="{{ route('register') }}">Register</a> | <a href="{{ route('password.request') }}">Forgot Password?</a>
        </form>
            <a class="login-box-submit-button" href="login/facebook">Facebook</a>
            <a class="login-box-submit-button" href="login/github">Github</a>
    </section>
    <section role="tabpanel" aria-hidden="true" id="register-tab">
        <form class="medium-6 columns centered" role="form" method="POST" action="{{ route('register') }}">
                {{ csrf_field() }}
                <h3 class="text-center">Register</h3>
                <label>Name <input id="name" type="text" name="name" value="{{ old('name') }}" autocomplete='name' required autofocus></label>
                <label>E-Mail Address <input id="email" type="email" name="email" autocomplete="email" value="{{ old('email') }}" required></label>
                <label>Password <input id="password" type="password" name="password" autocomplete="new-password" required></label>
                <label>Confirm Password <input id="password-confirm" type="password" name="password_confirmation" autocomplete="new-password" required></label>
                {!! app('captcha')->render() !!}
                <button type="submit" class="button expand">Register</button>
            </div>
        </form>
    </section>
@endsection
