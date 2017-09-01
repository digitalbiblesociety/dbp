@extends('layouts.app')

@section('head')
    <style>
        small {
            display: block
        }
        .card {
            display: block;
            background: #f8f8f8;
            border:thin solid #ccc;
            text-align: center;
            padding:20px 15px;
            margin:10px 0;
            color:#222;
        }
        .card img {
            width:25%;
            float:left;
        }

        .card:hover {
            background: #f0f0f0;
        }
        .title {
            display: block;
        }
    </style>
@endsection

@section('content')

    <div class="row text-center">
        <h1>Hello {{ $user->name }}</h1>
        <p>You have 0 Alerts requiring your attention.</p>
        <p>You are awaiting 5 replies from other organizations or users</p>
    </div>

    <div class="row">
        <div class="medium-4 columns">
            <h2>Access</h2>
            <p>Your current organization status is:
                @if(count($user->roles) > 0)
                    @foreach($user->roles as $connection)
                        <a href="{{ route('dashboard_organizations.show',['id' => $connection->organization->id]) }}" class="card">
                            <img src="{{ $connection->organization->logoIcon->icon or $connection->organization->logo->url }}" />
                            <span class="title">{{ $connection->organization->translations("eng")->first()->name }}</span>
                            <small class="subtitle">{{ $connection->role }}</small>
                            @if(($connection->role == "manager") | ($connection->role == "admin"))
                                <a href=""></a>
                            @endif
                        </a>
                    @endforeach
                    <a href="{{ route('dashboard_organizations.create') }}" class="button">Add an Organization</a>
                @else
                    <strong>NOT SET?! <a href="{{ route('dashboard_organization_roles.create') }}" class="button">Request to join one now!</a></strong>
                @endif
            </p>
        </div>
        <div class="medium-4 columns">
            <h2>Connected Accounts</h2>
            <p>Your current connected accounts are:
                <ul class="organization connections">
                    <li @if(!$user->github) class="disabled" @endif>Github</li>
                    <li @if(!$user->google) class="disabled" @endif><svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="https://upload.wikimedia.org/wikipedia/commons/5/53/Google_%22G%22_Logo.svg"></use></svg>Google</li>
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
