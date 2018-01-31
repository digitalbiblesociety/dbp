@extends('layouts.app')
@section('head')
    <style>
        .panel {
            background-color:#f1f1f1;
            margin-top:15px;
            padding:20px;
        }

        .role {
            text-transform: capitalize;
            text-align: center;
        }
        .role.requesting-access {
            color:#888;
        }
    </style>
@endsection

@section('content')

    @include('layouts.banner', [
    'title' => trans('dashboard.organization_roles'),
    //'subtitle'  => trans('about.page_bibles_subtitle'),
    //'icon'      => '/img/icons.svg#bibles',
    //'iconClass' => 'page-icon',
    //'iconSVG'   => true,
    //'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwAAAwEAAAAAAAAAAAAAAAAAAAECA//EABkQAQADAQEAAAAAAAAAAAAAAAABAjEhQf/EABQBAQAAAAAAAAAAAAAAAAAAAAD/xAAUEQEAAAAAAAAAAAAAAAAAAAAA/9oADAMBAAIRAxEAPwCZsdZEAD1OLT6BT0Vo0AP/2Q==',
    //'backgroundImage' => "http://images.bible.cloud/fab/banners/banner_bibles.jpg",
    'breadcrumbs' => [
        route('home')   => trans('dashboard.home_title'),
        '#'             => trans('dashboard.organization_roles')
    ]
])

    @foreach($user->roles as $role)
        <div class="medium-4 columns">
            <div class="panel text-center">
                <h4>{{ $role->organization->currentTranslation->name }}</h4>
                <div class="role {{$role->role}}">{{ str_replace('-',' ',$role->role) }}</div>
                <a href="mailto:{{ $role->organization->email  }}">Request Update</a> |
                <a href="/organizations/{{ $role->organization_id }}">See Organization Page</a>
            </div>
        </div>
    @endforeach

@endsection