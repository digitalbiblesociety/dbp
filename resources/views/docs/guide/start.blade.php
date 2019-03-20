@extends('layouts.app')

@section('head')
    <style>
        h1 {
            font-size: medium;
            text-align: center;
        }

        h2 {
            font-size: 16px
        }

        h3 {
            font-size: 26px;
            font-weight: 400;
            margin-top: 45px;
            margin-bottom: 15px;
            position: relative;
            text-transform: none;
            color: #666;
            text-indent: 25px;
        }
        h3:before {content: "#"}

        dl dt {
            text-indent: 35px;
        }

        section code {
            font-family: "Operator Mono", "Fira Code", Consolas, Monaco, "Andale Mono", monospace;
        }

        a {
            color: #88bb44;
        }

        #signup {
            position: relative;
            display: inline-block;
        }

        #signup #request {
            line-height: normal;
            display: inline-block;
            background: #c83c64;
            border-radius: 3px;
            padding: 10px 14px;
            border: none;
            cursor: pointer;
            color: white;
            font-size: 15px;
            width: 180px;
            text-align: center;
            position: relative;
            transition: all 350ms cubic-bezier(0.23, 1 ,0.32, 1);
            -webkit-transition: all 350ms cubic-bezier(0.23, 1 ,0.32, 1);
            -moz-transition: all 350ms cubic-bezier(0.23, 1 ,0.32, 1);

        }

        #signup #request:hover {
            background: #d34a5b;
        }

        #signup #request:focus {
            outline: none;
        }

        #signup #request.email {
            width: 280px;
            text-align: left;
            cursor: text;
            padding-right: 34px;
        }

        #signup .email-send {
            position: absolute;
            top: 7px;
            right: 10px;
            width: 24px;
            height: 24px;
            background: url(http://journal.mathieurobert.fr/img/icon-send.svg);
            background-color: white;
            background-size: 24px;
            border-radius: 3px;
            cursor: pointer;
            opacity: 0;
            transform: scale(0.5);
            -webkit-transform: scale(0.5);
            -moz-transform: scale(0.5);
            -webkit-transition: all 200ms cubic-bezier(0.175, 0.885 ,0.32, 1.275) 50ms;
            -moz-transition: all 200ms cubic-bezier(0.175, 0.885 ,0.32, 1.275) 50ms;
        }

        #signup .email-send.email-send-show {
            opacity: 0.6;
            transform: scale(1);
            -webkit-transform: scale(1);
            -moz-transform: scale(1);
        }

        #signup .email-send.email-send-show:hover {
            opacity: 0.8;
        }

        ::-webkit-input-placeholder { color: white;}
        :-moz-placeholder { color: white;}
        ::-moz-placeholder { color: white;}
        :-ms-input-placeholder { color: white;}

        .email::-webkit-input-placeholder { color: rgba(255,255,255,0.6);}
        .email:-moz-placeholder { color: rgba(255,255,255,0.6);}
        .email::-moz-placeholder { color: rgba(255,255,255,0.6);}
        .email:-ms-input-placeholder { color: rgba(255,255,255,0.6);}


    </style>

@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => 'Getting Started',
    ])

    <div class="container">
    <div class="columns">
    <div class="column is-3-desktop">
    <div class="box">
        <aside class="menu">
            <p class="menu-label">Getting Started</p>
            <ul class="menu-list">
                <li><a class="is-disabled has-text-grey-light">First Call & Getting an API key</a></li>
                <li><a class="is-disabled has-text-grey-light">Building a Menu</a></li>
                <li><a class="is-disabled has-text-grey-light">Getting Bible Text</a></li>
            </ul>
            <p class="menu-label has-text-grey-light">
                Annotations
            </p>
            <ul class="menu-list">
                <li><a class="is-disabled has-text-grey-light">Getting Started</a></li>
                <li><a class="is-disabled has-text-grey-light">Creating Highlights, Bookmarks, and Notes</a></li>
                <li><a class="is-disabled has-text-grey-light">Sharing</a></li>
            </ul>
        </aside>
    </div>
    </div>

        <div class="column is-9-desktop">

            <section class="box">

                <h4 class="title">Generating and Confirming your API Key</h4>

                <div class="has-text-centered">
                @if(\Auth::user())
                    @if(\Auth::user()->keys()->count() === 0)
                        <a class="button is-primary" href="{{ route('dashboard.keys.create') }}"> Create a Key</a>
                    @else
                        <pre>{{ \Auth::user()->keys()->first() }}</pre>
                    @endif
                @else
                    <h4>Please Log in to have access to the API key generation</h4>
                @endif
                </div>

                <h4 class="title">The API Structure</h4>
                <h2 class="subtitle">Bibles and Filesets</h2>

                <div class="ribbon">If you want to jump right in to queries you can check out the <a href="{{ route('swagger', ['version' => 4]) }}">API reference documentation.</a></div>
                <p>Your first call to the DBP will probably be to /bibles. All Biblical content whether it be Audio, Video, or text is nested within bible_id.</p>
                <p>All calls within version 4 of the API are separated into three general categories: Bibles, Wiki, and Community.</p>

                <h4>Bibles</h4>
                <p>The routes categorized under the Bibles tag are generally focused on querying information about bibles and audio or text content of those Bibles.</p>

                <h4>Wiki</h4>
                <p>Languages, Countries, and alphabet Data</p>

                <h4>Community</h4>
                <p>
                    The community section of the API allows not only API users to create and manage their users.
                    But also allows for notes, cross-references, and highlights to be used across different apps that utilize the Koinos API.
                </p>

                <p>Common User Authorization Actions</p>

                <ul>
                    <p>
                        Most user password changes require a verification email to be sent to the user. The only exception to this rule is if the `old_password`
                        field is supplied to the /users/reset/password route.
                        You can trigger a password reset email by sending the /users/reset/password user's email.
                    </p>
                </ul>

                <p>Common User Notes Actions</p>
                <ul>
                    <li>Creating a note</li>
                    <li>Updating a note</li>
                    <li>Displaying a new note</li>
                </ul>

            </section>
        </div>

    </div>

    </div>

@endsection

@section('footer')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script>
	    $('#request').click(function(event){
		    event.stopPropagation();
		    $(this).addClass("email");
		    $('.email-send').addClass("email-send-show");
		    $(this).attr("placeholder", "Enter your email...");
	    });

	    $('html').click(function() {
		    $('#request').removeClass("email");
		    $('.email-send').removeClass("email-send-show");
		    $('#request').attr("placeholder", "Request Early Access");
	    });
    </script>

@endsection