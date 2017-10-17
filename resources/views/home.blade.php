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

        .user-banner {
            background: #333;
            height:50px;
            line-height:50px;
        }
        .user-banner a {
            float: right;
            padding:0 10px;
        }

        .user-banner .logo {
            background: #f0f0f0;
            width:50px;
            height:50px;
            border-radius: 100%;
            position: relative;
            margin:15px;
            text-align: center;
        }

        .user-banner .logo .badge {
            line-height:13px;
            position: absolute;
            bottom:-3px;
            right:-3px;
        }


        .connections a {
            height: 70px;
            display: block;
            line-height: 70px;
            color:#FFF;
            text-shadow: 0 0 1px #000000;
        }
        .connections a.disabled {
            opacity: .5;
        }

        .connections a img {
            height:50px;
            margin:10px;
            float:left;
        }

        .github         {background-color:#181717}
        .google         {background-color:#4285F4}
        .bitbucket      {background-color:#205081}

    </style>
@endsection

@section('content')

    <nav class="user-banner">
        <a class="logo" href="/user/{{ $user->id }}/alerts">A <span class="badge">4</span></a>
    </nav>

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
                    <a href="{{ route('dashboard_organization_roles.create') }}" class="button">Add an Organization</a>
                @else
                    <strong>NOT SET?! <a href="{{ route('dashboard_organization_roles.create') }}" class="button">Request to join one now!</a></strong>
                @endif
            </p>
        </div>
        <div class="medium-4 columns">
            <h2>Connected Accounts</h2>
            <p>Your current connected accounts are:
                <div class="organization connections">
                    <a href="https://github.com/" class="github @if(!$user->github) disabled @endif"><img src="/img/social/github.svg" /> Github</a>
                    <a href="https://google.com/" class="google @if(!$user->google) disabled @endif"><img src="/img/social/google.svg" /> Google</a>
                    <a href="https://bitbucket.org/" class="bitbucket @if(!$user->bitbucket) disabled @endif"><img src="/img/social/bitbucket.svg" /> Bitbucket</a>
                </div>
            </p>
        </div>
        <div class="medium-4 columns">
            <h2>Documentation</h2>
            <p>Read it, there's plenty to learn</p>
            <a class="button" href="/docs">Learn More</a>
        </div>
    </div>
    <div class="row">

        <div class="medium-4 columns">
            <h2>Create new Resources</h2>
            <ul>
                @if($user->roles->where('organization', '!=', null)->where('role','!=','requesting-access')->first())
                    <li><a href="/bibles/create">Create Bible</a></li>
                    <li><a href="/resources/create">Create Resource</a></li>
                @else
                    <li><span class="disabled">Create Bible</span></li>
                    <li><span class="disabled">Create Resource</span></li>
                    <small class="text-center">You need Organization Permissions to Edit Bible or Resource Data</small>
                @endif
                @if($user->archivist)
                    <li><a href="/languages/create">Create Language</a></li>
                    <li><a href="/alphabets/create">Create Alphabet</a></li>
                    <li><a href="/countries/create">Create Country</a></li>
                @else
                    <li><span class="disabled">Create Language</span></li>
                    <li><span class="disabled">Create Alphabet</span></li>
                    <li><span class="disabled">Create Country</span></li>
                    <small class="text-center">You need Archivist Permissions to Edit Language, Country, or Alphabet Data</small>
                @endif
            </ul>
        </div>
    </div>

@endsection
