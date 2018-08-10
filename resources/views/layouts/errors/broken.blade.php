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

    @include('layouts.partials.banner', ['title' => 'Error'])

    <div class="container">
        <div class="content">

            <div class="message is-danger">
                <div class="message-header">{{ trans('api.errors_'.$status) }}</div>
                <div class="message-body">@if(isset($message)) {{ trans($message) }} @endif</div>
            </div>

        </div>
    </div>

@endsection