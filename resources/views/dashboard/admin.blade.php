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

            <div class="column is-half is-offset-one-quarter">
                <label>Search
                <input class="input" type="search" placeholder="Bibles, Languages, Audio, Filesets..."></label>
            </div>

        </div>


        <div class="card">
            <header class="card-header">
                <p class="card-header-title">Arifama-Miniafia 2009 Edition <span class="has-text-grey">Tur Gewasin O Baibasit Boubun</span></p>
                <a href="#" class="card-header-icon is-size-7 has-text-grey-light" aria-label="more options">AAIWBT</a>
            </header>
            <div class="card-content">
                <div class="content">
                    <ul>
                        <li><a href="#">c0ba31fc8eb6 <small>text_plain</small> <b>NT</b></a></li>
                        <li><a href="#">aea24f4d65f1 <small>text_format</small> <b>NT</b></a></li>
                        <li><a href="#">1c6bb3218588 <small>text_plain</small> <b>NT</b></a></li>
                    </ul>
                    <br>
                    <time datetime="2016-1-1">Last Updated Jan 1st 2016 @11:09 PM</time>
                </div>
            </div>
        </div>

</div>

@endsection


@section('footer')
    <script>

    </script>
@endsection