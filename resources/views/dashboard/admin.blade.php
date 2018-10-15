@extends('layouts.app')

@section('head')
    <style>
        .card-header-title {flex-direction: column;align-items:left;-webkit-box-align:left}
    </style>
@endsection


@section('content')

    @include('layouts.partials.banner', [
        'title'     => 'Welcome '. $user->name,
        'subtitle'  => 'Admin Access'
    ])

    @include('dashboard.organizations.partials.sync-dbl-message',$user)
    <div class="container">

        <div class="columns">
            <a href="{{ route('profile.home') }}"> Profile </a>
        </div>

        <div class="columns">

            <div class="column">

                @foreach($user->roles as $role)
                    <a class="button" href="{{ route('dashboard.tasks', ['role' => $role->slug]) }}">{{ $role->name }} Tasks</a>
                @endforeach

            </div>

        </div>

</div>

@endsection


@section('footer')
    <script>

    </script>
@endsection