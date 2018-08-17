@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => __('Reset Password')
    ])

    <div class="container">
        @if (session('status'))<div class="alert alert-success">{{ session('status') }}</div> @endif

        <div class="columns">
            <form class="column is-half is-offset-one-quarter" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="box">
                    <input class="input" type="hidden" name="project_id" value="{{ $project->id ?? null }}" required>
                    <div class="field">
                        <label class="label" for="email">{{ __('E-Mail Address') }}</label>
                        <div class="control"><input class="input" type="email" autocomplete="email" name="email" value="{{ old('email') }}" required autofocus placeholder="Email"></div>
                        @if($errors->has('email')) <p class="help is-danger">{{ $errors->first('email') }}</p> @endif
                    </div>
                    <button type="submit" class="button">{{ __('Send Password Reset Link') }}</button>
                </div>
            </form>
        </div>

    </div>

@endsection
