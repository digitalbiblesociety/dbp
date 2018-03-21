@extends('layouts.app')

@section('head')
    <style>
        h1 {
            text-align: center;
        }
    </style>
@endsection

@section('content')

    <H1>Edit {{ $user->name }}</H1>

    <form action="{{ route('users.update', ['id' => $user->id ]) }}" method="POST" enctype="multipart/form-data" data-abide novalidate>
        {{ csrf_field() }}
        {{ method_field('PUT') }}
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