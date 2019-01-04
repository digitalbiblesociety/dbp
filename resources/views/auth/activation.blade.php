@extends('layouts.app')

@section('head')
    <title>	{{ trans('app.activation') }}</title>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => trans('app.activation')])

	<div class="container">

        <div class="card">
        	<div class="card-header">{{ trans('app.activation') }}</div>
        	<div class="card-content">
        		<p>{{ trans('auth.regThanks') }}</p>
        		<p>{{ trans('auth.anEmailWasSent',['email' => $email, 'date' => $date ] ) }}</p>
        		<p>{{ trans('auth.clickInEmail') }}</p>
                <footer class="card-footer">
        		    <a href='/activation' class="card-footer-item">{{ trans('auth.clickHereResend') }}</a>
                </footer>
        	</div>
        </div>

	</div>
@endsection
