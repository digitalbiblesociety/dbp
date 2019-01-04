@extends('layouts.app')

@section('head')
    <title>{{ trans('app.exceeded') }}</title>
@endsection

@section('content')
	<div class="container">

        <div class="message is-danger">
        	<div class="message-heading">{{ trans('app.exceeded') }}</div>
        	<div class="message-body"><p>{!! trans('auth.tooManyEmails', ['email' => $email, 'hours' => $hours]) !!}</p></div>
        </div>

	</div>
@endsection
