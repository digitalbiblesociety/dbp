@extends('layouts.app')

@section('head')
    <style>
        .card {
            display: block;
            padding:20px;
            background-color: #f8f8f8;
            border:thin solid #ccc;
            text-align: center;
        }
        .card:hover {
            border:thin solid #999;
        }
        .card img {
            display: block;
            margin:0 auto;
        }
        .title {
            display: block;
            padding-top:20px;
            font-size:1.2rem;
            letter-spacing: 1px;
            color:#1f1f1f;
        }
        .role {
            color:#888;
        }

        .requesting-access {
            opacity: .5;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <h1 class="text-center">Organizations</h1>
            @foreach($user->role as $role)
                    <div class="medium-4 columns {{ $role->role }}">
                        <a class="card" href="{{ route('dashboard_organizations.show', ['id' => $role->organization->id]) }}">
                            <img src="{{ $role->organization->logo->url }}"/>
                            <span class="title">{{ $role->organization->translations("eng")->first()->name }}</span>
                            <span class="role">{{ trans('fields.'.$role->role) }}</span>
                        </a>
                    </div>
            @endforeach
    </div>
@endsection