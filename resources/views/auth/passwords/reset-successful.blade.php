@extends('layouts.app')

@section('head')
    <style>
        .reset-successful {
            margin-top: 50px;
        }
    </style>
@endsection

@section('content')

    <div class="container">
        <div class="box reset-successful">
            <h2 class="has-text-centered is-size-4">Reset Successful</h2>
            @if($reset_path)
                <a href="{{ $reset_path }}">Continue to {{ $reset_path }}</a>
            @endif
        </div>
    </div>

@endsection