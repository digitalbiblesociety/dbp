@extends('layouts.app')

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

    .without-organization {
        height:100%;
    }
    .without-organization div {
        padding:20px;
    }

    .organization-join-panel {
        height:100%;
        background: #f1f1f1;
        text-align: center;
    }
    .without-organization h2 {
        font-size:1.75rem;
    }

    .organizations {
        background-color:#f1f1f1;
        text-align: center;
    }

    .organizations img {
        height:70px;
        margin:10px auto;
    }

    .organizations a {
        color:#222;
        background-color:rgba(0,0,0,.1);
        margin:10px;
    }

    .organizations a:hover {
        background-color:rgba(0,0,0,.8);
        color:#FFF;
    }

</style>
@section('head')
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => "Hello $user->name" ])

    @if(count($user->roles) > 0)
        <div class="row organizations">
        @foreach($user->roles as $connection)
            <a href="{{ route('dashboard_organizations.show',['id' => $connection->organization->id]) }}" class="medium-2 columns">
                <img src="{{ $connection->organization->logo->url }}" title="{{ $connection->organization->translations("eng")->first()->name }}" />
                <small class="subtitle">{{ $connection->role }}</small>
            </a>
        @endforeach
            <a href="{{ route('dashboard_organization_roles.create') }}" class="medium-2 columns">
                <img src="/img/icons/add.svg" />
                <small class="subtitle">Add an Organization</small>
            </a>
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

    @else
        <div class="row expanded callout secondary text-center">
            <p>Your API Key is <h5><a href="{{ route('view_bible_filesets_permissions.user') }}"><code>{{ $user->id }}</code></a></h5></p>
        </div>
        <div class="row expanded without-organization">
            <div class="medium-6 columns organization-join-panel">
                <h2>Your account is not associated with an organization</h2>
                <div class="medium-8 columns centered">
                <p>If you want to do things like add new Bibles or edit meta data information you'll need to join one</p>
                <a href="{{ route('dashboard_organization_roles.create') }}" class="button expanded">Request to join one now!</a>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="163.1" height="151.7">
                    <g transform="translate(-747.63 -930.078)">
                        <path d="M772.5 976.8c-4.7 0-8.5 3.8-8.5 8.4 0 4.7 3.8 8.5 8.5 8.5 4.6 0 8.4-3.8 8.4-8.5 0-4.6-4-8.4-8.5-8.4zm-8 16.4c-2.4.5-4 2.5-4 5v13c0 2.5 1.6 4.6 4 5h.2v16.3c0 1.5 1.2 2.8 2.7 2.8h10.2c1.5 0 2.7-1.3 2.7-2.8v-16.2h.2c2.4-.5 4.2-2.6 4.2-5v-13c0-2.6-1.8-4.6-4-5-2 2.3-5 3.8-8.2 3.8-3.2 0-6-1.4-8-3.8zM865.2 988.2c-5.2 0-9.4 4.2-9.4 9.4s4.2 9.5 9.4 9.5 9.5-4.2 9.5-9.4-4.3-9.4-9.5-9.4zm-9 18.4c-2.5.5-4.5 2.8-4.5 5.6v14.5c0 2.8 2 5 4.6 5.7h.3v18c0 1.8 1.3 3.2 3 3.2H871c1.6 0 3-1.4 3-3v-18.2h.2c2.7-.6 4.6-3 4.6-5.7v-14.5c0-2.8-2-5-4.5-5.6-2 2.6-5.4 4.3-9 4.3-3.6 0-6.8-1.8-9-4.4z" fill="#999" overflow="visible"/>
                        <path d="M814.6 981.6c-6.2 0-11.4 5-11.4 11.4 0 6.2 5.2 11.3 11.4 11.3 6.3 0 11.4-5 11.4-11.3 0-6.3-5-11.4-11.4-11.4zm-10.7 22c-3.3.8-5.6 3.5-5.6 7v17.3c0 3.3 2.3 6 5.5 6.7h.2v22c0 2 1.6 3.5 3.7 3.5h13.5c2 0 3.7-1.6 3.7-3.6v-22h.2c0 .2 0 0 0 0 3.3-.5 5.6-3.3 5.6-6.7v-17.5c0-3.4-2.3-6-5.5-6.8-2.5 3.2-6.4 5.3-10.8 5.3s-8.2-2-10.7-5.3z" fill="#00b09b" overflow="visible"/>
                        <path d="M843.3 957.3c-3.7 0-6.8 3-6.8 7 0 3.6 3 6.7 6.8 6.7s7-3 7-6.8-3.2-7-7-7zm-6.4 13.3c-2 .4-3.4 2-3.4 4v10.6c0 2 1.4 3.8 3.3 4v13.3c0 1.2 1 2.2 2.3 2.2h8.2c1.2 0 2.2-1 2.2-2.2v-13.2h.2c1.8-.3 3.2-2 3.2-4v-10.6c0-2-1.4-3.7-3.3-4-1.6 2-4 3-6.6 3-2.7 0-5-1-6.5-3zM888.3 968.6c-3.3 0-6 2.7-6 6s2.7 6 6 6 6-2.7 6-6-2.7-6-6-6zm-5.7 11.7c-1.6.3-3 1.8-3 3.6v9c0 1.8 1.4 3.3 3 3.6h.2v11.6c0 1 .8 2 2 2h7c1.2 0 2-1 2-2v-11.6h.2c1.7-.3 3-1.8 3-3.5v-9c0-2-1.3-3.4-3-3.7-1.3 1.7-3.4 2.8-5.7 2.8-2.3 0-4.3-1-5.6-2.7zM793.4 962.4c-2.8 0-5 2.3-5 5 0 3 2.2 5.2 5 5.2s5-2.3 5-5c0-3-2.2-5.2-5-5.2zm-4.8 10c-1.5.2-2.5 1.4-2.5 3v7.7c0 1.6 1 3 2.6 3.2v9.8c0 1 .8 1.6 1.7 1.6h6.2c1 0 1.6-.7 1.6-1.6v-9.8h.2c1.4-.3 2.5-1.6 2.5-3v-8c0-1.4-1-2.6-2.5-3-1 1.5-2.8 2.4-4.8 2.4s-3.7-1-4.8-2.3z" fill="#999" overflow="visible"/>
                    </g>
                </svg>

            </div>
            <div class="medium-6 columns text-center">
                <h2>You can query the API or Read Documentation</h2>
                <div class="medium-8 columns centered">
                <p>You don't need an official organization to query information about Bibles.</p>
                <a href="{{ route('swagger') }}" class="button expanded">Docs!</a>
                </div>
            </div>
        </div>
    @endif



@endsection
