@extends('layouts.app')

@section('head')
    <style>
        .message {
            max-width:400px;
            margin:0 auto;
            text-align: center;
        }
    </style>
@endsection

@section('content')

    {{-- Narsil --}}

    @include('layouts.partials.banner', ['title' => 'Verification Required'])

    <div class="container">

            <div class="message is-info">
                <div class="message-header">Email Sent</div>
                <div class="message-body">Please check your email for the verification URL</div>
            </div>

    </div>

@endsection