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

    .user {
        background-color: #f1f1f1;
        margin-top:50px;
        position: relative;
    }

    .user input {
        float:right;
        width: calc(100% - 85px);
    }

    .user_avatar {
        width:80px;
        float:left;
        margin:20px 5px 0 0;
    }

</style>

@endsection

@section('content')

@if(isset($user) or \Auth::user())
    @if($user->admin)
        @include('layouts.partials.banner', ['title' => "Admin" ])
        @include('dashboard.admin')
    @elseif($user->archivist)
        @include('layouts.partials.banner', ['title' => "Welcome Archivist $user->name" ])
        @include('dashboard.archivist')
    @else
        @if(!$user->verified)
            @include('layouts.partials.banner', ['title' => "You have successfully registered. Please check your email for the verification email." ])
        @else
            @include('layouts.partials.banner', ['title' => "Hello $user->name" ])
            @include('dashboard.user')
        @endif
    @endif
@else
    <h3 class="text-center">It seems like<br> you're not logged in.</h3>
@endif




@endsection
