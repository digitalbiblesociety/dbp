@extends('layouts.app')

@section('head')
    <style>
        label,
        input {
            display: block;
            width:100%;
        }


        .login-box {
            box-shadow: 0 2px 4px rgba(10, 10, 10, 0.4);
            background: #fefefe;
            border-radius: 0;
            overflow: hidden;
            position: relative;
            margin-top:100px;
        }

        .login-box .or {
            position: absolute;
            top: 50%;
            left: 50%;
            -webkit-transform: translate(-50%, -50%);
            -ms-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
            display: inline-block;
            min-width: 2.1em;
            padding: 0.3em;
            border-radius: 50%;
            font-size: 0.6rem;
            text-align: center;
            font-size: 1.275rem;
            background: #cacaca;
            box-shadow: 0 2px 4px rgba(10, 10, 10, 0.4);
        }

        @media screen and (max-width: 39.9375em) {
            .login-box .or {
                top: 85%;
            }
        }

        .login-box-title {
            font-weight: 300;
            font-size: 1.875rem;
            margin-bottom: 1.25rem;
        }

        .login-box-form-section,
        .login-box-social-section-inner {
            padding: 2.5rem;
        }

        .login-box-social-section {
            background: url("https://images.pexels.com/photos/179124/pexels-photo-179124.jpeg?w=1260&h=750&auto=compress&cs=tinysrgb");
            background-size: cover;
            background-position: center;
        }

        .login-box-input {
            margin-bottom: 1.25rem;
            height: 2rem;
            border: 0;
            padding-left: 0;
            box-shadow: none;
            border-bottom: 1px solid #1779ba;
            font-weight: 400;
        }

        .login-box-input:focus {
            color: #1779ba;
            transition: 0.2s ease-in-out;
            box-shadow: none;
            border: 0;
            border-bottom: 2px solid #1779ba;
        }

        .login-box-submit-button {
            display: inline-block;
            vertical-align: middle;
            margin: 0 0 1rem 0;
            padding: 0.85em 1em;
            -webkit-appearance: none;
            border: 1px solid transparent;
            border-radius: 0;
            transition: background-color 0.25s ease-out, color 0.25s ease-out;
            font-size: 0.9rem;
            line-height: 1;
            text-align: center;
            cursor: pointer;
            background-color: #1779ba;
            color: #fefefe;
            display: block;
            width: 100%;
            margin-right: 0;
            margin-left: 0;
            border-radius: 0;
            text-transform: uppercase;
            margin-bottom: 0;
        }

        [data-whatinput='mouse'] .login-box-submit-button {
            outline: 0;
        }

        .login-box-submit-button:hover, .login-box-submit-button:focus {
            background-color: #126195;
            color: #fefefe;
        }

        .login-box-submit-button:hover,
        .login-box-submit-button:focus {
            box-shadow: 0 2px 4px rgba(10, 10, 10, 0.4);
        }

        .login-box-submit-button:active {
            box-shadow: 0 1px 2px rgba(10, 10, 10, 0.4);
        }

        .login-box-social-button-facebook {
            display: inline-block;
            vertical-align: middle;
            margin: 0 0 1rem 0;
            padding: 0.85em 1em;
            -webkit-appearance: none;
            border: 1px solid transparent;
            border-radius: 0;
            transition: background-color 0.25s ease-out, color 0.25s ease-out;
            font-size: 0.9rem;
            line-height: 1;
            text-align: center;
            cursor: pointer;
            background-color: #3b5998;
            color: #fefefe;
            display: block;
            width: 100%;
            margin-right: 0;
            margin-left: 0;
            font-weight: 500;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
        }

        [data-whatinput='mouse'] .login-box-social-button-facebook {
            outline: 0;
        }

        .login-box-social-button-facebook:hover, .login-box-social-button-facebook:focus {
            background-color: #2f477a;
            color: #fefefe;
        }

        .login-box-social-button-twitter {
            display: inline-block;
            vertical-align: middle;
            margin: 0 0 1rem 0;
            padding: 0.85em 1em;
            -webkit-appearance: none;
            border: 1px solid transparent;
            border-radius: 0;
            transition: background-color 0.25s ease-out, color 0.25s ease-out;
            font-size: 0.9rem;
            line-height: 1;
            text-align: center;
            cursor: pointer;
            background-color: #55acee;
            color: #fefefe;
            display: block;
            width: 100%;
            margin-right: 0;
            margin-left: 0;
            font-weight: 500;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
        }

        [data-whatinput='mouse'] .login-box-social-button-twitter {
            outline: 0;
        }

        .login-box-social-button-twitter:hover, .login-box-social-button-twitter:focus {
            background-color: #1a8fe8;
            color: #fefefe;
        }

        .login-box-social-button-google {
            display: inline-block;
            vertical-align: middle;
            margin: 0 0 1rem 0;
            padding: 0.85em 1em;
            -webkit-appearance: none;
            border: 1px solid transparent;
            border-radius: 0;
            transition: background-color 0.25s ease-out, color 0.25s ease-out;
            font-size: 0.9rem;
            line-height: 1;
            text-align: center;
            cursor: pointer;
            background-color: #dd4b39;
            color: #fefefe;
            display: block;
            width: 100%;
            margin-right: 0;
            margin-left: 0;
            font-weight: 500;
            margin-bottom: 1.25rem;
            text-transform: uppercase;
        }

        [data-whatinput='mouse'] .login-box-social-button-google {
            outline: 0;
        }

        .login-box-social-button-google:hover, .login-box-social-button-google:focus {
            background-color: #be3221;
            color: #fefefe;
        }

        [class*="login-box-social-button-"]:hover,
        [class*="login-box-social-button-"]:focus {
            box-shadow: 0 2px 4px rgba(10, 10, 10, 0.4);
        }

        .login-box-social-headline {
            display: block;
            margin-bottom: 2.5rem;
            font-size: 1.875rem;
            color: #fefefe;
            text-align: center;
        }

        .button.github {background:#000;color:#FFF;text-transform: uppercase}

    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => "Login" ])

    <div class="row row-padding align-center">
        <div class="login-box row">
            <div class="small-12 medium-6 columns small-order-2 medium-order-1">
                <div class="login-box-form-section">
                    <form role="form" method="POST" action="{{ route('login') }}">
                        {{ csrf_field() }}
                        <h1 class="login-box-title">Sign in</h1>
                        <input class="login-box-input" type="text" name="username" placeholder="Username" value="{{ old('email') }}" required />
                        <input class="login-box-input" type="password" name="password" placeholder="Password" required />
                        <input class="login-box-submit-button" type="submit" name="signup_submit" value="Sign In" />
                        <a href="{{ route('register') }}">Register</a> | <a href="{{ route('password.request') }}">Forgot Password?</a>
                    </form>
                </div>
            </div>
            <div class="or">OR</div>
            <div class="small-12 medium-6 columns small-order-1 medium-order-2 login-box-social-section">
                <div class="login-box-social-section-inner">
                    <span class="login-box-social-headline">Sign in with<br />your social network</span>
                    <a class="login-box-social-button-facebook" href="{{ route('login.social_redirect', ['provider' => 'facebook']) }}">Log in with facebook</a>
                    <a class="login-box-social-button-twitter" href="{{ route('login.social_redirect', ['provider' => 'twitter']) }}">Log in with Twitter</a>
                    <a class="button expanded github" href="{{ route('login.social_redirect', ['provider' => 'github']) }}" href="">Github Login</a>
                </div>
            </div>
        </div>
    </div>



    {{--
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
    --}}
@endsection
