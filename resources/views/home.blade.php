@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="medium-4 columns">
            <h2>Access</h2>
            <p>Your current access level is: {{ $user->role or "none" }}</p>
        </div>
        <div class="medium-4 columns">
            <h2>Connected Accounts</h2>
            <p>Your current connected accounts are:
                <ul>
                    <li @if(!$user->github) class="disabled" @endif>Github</li>
                    <li @if(!$user->google) class="disabled" @endif>Google</li>
                    <li @if(!$user->bitbucket) class="disabled" @endif>Bitbucket</li>
                </ul>
            </p>
        </div>
        <div class="medium-4 columns">
            <h2>Documentation</h2>
            <p>Read it, there's plenty to learn</p>
            <a class="button" href="/docs">Learn More</a>
        </div>
    </div>

@endsection
