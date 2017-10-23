@extends('layouts.app')

@section('content')

    @include('layouts.banner', ['title' => 'Reset Password'])
    
    @if(session('status'))<div class="callout success">{{ session('status') }}</div>@endif
    @if($errors->has('email')) <div class="callout alert">{{ $errors->first('email') }}</div> @endif

    <form role="form" class="medium-8 columns centered" method="POST" action="{{ route('password.email') }}">
        {{ csrf_field() }}
        <label for="email">E-Mail Address <input id="email" type="email" name="email" value="{{ old('email') }}" required></label>
        <button type="submit" class="button">Send Reset Link</button>
    </form>

@endsection
