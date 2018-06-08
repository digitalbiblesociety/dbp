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

        .table thead {
            display: none;
        }

        .table tr {
            display: block;
            width:120px;
            height:120px;
            background-color:#FFF;
            padding:5px;
            float:left;
            overflow: hidden;
        }

        .table tr a {
            color:#222;
        }

    </style>
@endsection

@section('content')

    <div class="row">
        <h1 class="text-center">Organizations</h1>
        <a href="https://dbp.dev/docs/swagger/v4#/Community">Version 4</a>
        <p>The Digital Bible Platform is a beneficiary of the hard work done to catalogue Biblical organizations by the find a bible project in collaboration with the forum of Bible Agencies and Digital Bible Society.
           The API now makes that data available as a public service. Organizations within this route may not be affiliated or endorsed with the DBP project. To view the organizations which currently utilize the API please see the projects section.</p>
        @isset($user)
            @foreach($user->role as $role)
                    <div class="medium-4 columns {{ $role->role }}">
                        <a class="card" href="{{ route('dashboard_organizations.show', ['id' => $role->organization->id]) }}">
                            <img src="{{ $role->organization->logo->url }}"/>
                            <span class="title">{{ $role->organization->translations("eng")->first()->name }}</span>
                            <span class="role">{{ trans('fields.'.$role->role) }}</span>
                        </a>
                    </div>
            @endforeach
        @endisset

        <div class="row">
            <table class="table" cellspacing="0" width="100%" data-route="organizations">
                <thead>
                <tr>
                    <th data-column-name="logos[0].url" data-image="true">{{ trans('fields.id') }}</th>
                    <th data-column-name="name" data-link="slug">{{ trans('fields.name') }}</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

    </div>
@endsection