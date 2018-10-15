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

        section {
            width: 800px;
            margin: 0 auto;
        }

        section p {
            font-size: 14px;
            text-align: justify;
            line-height: 1.8;
        }

        section a {
            background: #f0f2f1;
            padding: 1px 5px;
            border-radius: 3px;
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


    <h1>Digital Bible Platform: Koinos</h1>


    <section>

        <h3>API Organization</h3>
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
            <li></li>
            <li></li>
            <li></li>
            <p>
                Most user password changes require a verification email to be sent to the user. The only exception to this rule is if the `old_password`
                field is supplied to the <a href="/docs/swagger/v4#/Community/v4_user_reset2">/users/reset/password</a> route.
                You can trigger a password reset email by sending the <a href="/docs/swagger/v4#/Community/v4_user_reset1">/users/reset/password</a> user's email.
            </p>
        </ul>

        <p>Common User Notes Actions</p>
        <ul>
            <li>Creating a note</li>
            <li>Updating a note</li>
            <li>Displaying a new note</li>
        </ul>

    </section>
@endsection