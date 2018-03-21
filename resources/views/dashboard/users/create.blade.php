@extends('layouts.app')

@section('head')
    <style>
        h1 {
            text-align: center;
        }
    </style>
@endsection

@section('content')

    <form action="/dashboard/users/" method="POST" data-abide novalidate>
        {{ csrf_field() }}
        @if($errors->any())
            <div data-abide-error class="alert callout">
                <p><i class="fi-alert"></i> There are some errors in your form:</p>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @include('dashboard.users.form')
    </form>

@endsection