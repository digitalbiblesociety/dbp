@extends('layouts.app')

@section('head')

@endsection

@section('content')

@include('layouts.partials.banner', [
    'title' => 'Joining',
    'breadcrumbs' => [
        '/'      => 'Home',
        '/about' => 'About',
        '#'      => 'Joining'
    ]
])

    <div class="container">

        <div class="box">
            <section>
                <h2 class="is-size-4">Joining as a User</h2>
                <p>There are no barriers to getting an API key for the DBP. As soon as you sign up you’ll be able to query a good number of public domain and creative commons bibles. By using the API and getting an API key you agree to our the End user License agreement.</p>
            </section>
            <section class="mt20">
                <h2 class="is-size-4">Accessing Copyrighted Texts</h2>
                <p>Since the Digital Bible Platform is planning to interface directly with the Digital Bible Library an easy system will soon be developed to connected the texts that you have access to on the Digital Bible Library to also grant you access on the Digital Bible Platform if the agreement permits it. Otherwise you can request additional access directly through the your DBP dashboard.</p>
            </section>
        </div>

    </div>

@endsection