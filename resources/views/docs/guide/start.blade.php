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
                <li><a class="is-disabled has-text-grey-light">First Call</a></li>
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

                <h4 class="title">The API Structure</h4>
                <h2 class="subtitle">Bibles and Filesets</h2>

                <div class="ribbon">If you want to jump right in to queries you can check out the <a href="{{ route('swagger_v4') }}">API reference documentation.</a></div>

                <p>Your first call to the DBP will probably be to /bibles. All Biblical content whether it be Audio, Video, or text is nested within bible_id.</p>
                <pre>
    - ENGKJV
        - Filesets:
            - ENGKJVE2CT
                -
            - 3149021394
            - ENGKJVE2DA
</pre>

                <p>All calls within version 4 of the API are separated into three general categories: Bibles, Wiki, and Community.</p>

                <h4>Bibles</h4>
                <p>The routes categorized under the Bibles tag are generally focused on querying information about bibles and audio or text content of those Bibles.</p>

                <h4>Wiki</h4>
                <p></p>

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