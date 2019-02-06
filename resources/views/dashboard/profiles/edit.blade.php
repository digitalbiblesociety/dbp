@extends('layouts.app')

@section('template_title')
    {{ trans('profile.templateTitle') }}
@endsection

@section('content')

    <div id="circle"></div>

    <div class="container">
        <div class="tabs is-centered">
            <ul>
                <li class="is-active"><a href="#">{{ trans('profile.editProfileTitle') }}</a></li>
                <li><a href="{{ route('register') }}">{{ trans('profile.editAccountTitle') }}</a></li>
                <li><a href="{{ route('register') }}">{{ trans('profile.editAccountAdminTitle') }}</a></li>
            </ul>
        </div>

        <form method="POST" action="{{ route('profile.update', $user->id) }}" class="form-horizontal" role="form">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <div class="columns">
                <div class="field column">
                    <label class="label">Username</label>
                    <div class="control"><input class="input @if($errors->has('name')) is-danger @endif" type="text" placeholder="Name" value="{{ $user->name ?? old('name') }}"></div>
                    @if($errors->has('name')) <p class="help is-danger">{{ $errors->first('name') }}</p> @endif
                </div>
                <div class="field column">
                    <label class="label">Location</label>
                    <div class="control"><input class="input @if($errors->has('location')) is-danger @endif" type="text" placeholder="Location" value="{{ $user->location ?? old('location') }}"></div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('location') }}</p> @endif
                </div>
            </div>
            <div class="columns">
                <div class="field column">
                    <label class="label">Email</label>
                    <div class="control"><input class="input @if($errors->has('email')) is-danger @endif" type="text" placeholder="Name" value="{{ $user->email ?? old('email') }}"></div>
                    @if($errors->has('email')) <p class="help is-danger">{{ $errors->first('email') }}</p> @endif
                </div>
                <div class="field column">
                    <label class="label">First Name</label>
                    <div class="control"><input class="input @if($errors->has('first_name')) is-danger @endif" type="text" placeholder="First Name" value="{{ $user->first_name ?? old('first_name') }}"></div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('first_name') }}</p> @endif
                </div>
                <div class="field column">
                    <label class="label">Last Name</label>
                    <div class="control"><input class="input @if($errors->has('last_name')) is-danger @endif" type="text" placeholder="Last Name" value="{{ $user->last_name ?? old('last_name') }}"></div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('last_name') }}</p> @endif
                </div>
            </div>
            <div class="columns">
                <div class="field column">
                    <label class="label">Bio</label>
                    <div class="control"><textarea class="textarea">{{ $user->bio ?? old('bio') }}</textarea></div>
                    @if($errors->has('bio')) <p class="help is-danger">{{ $errors->first('bio') }}</p> @endif
                </div>
            </div>
        </form>

    </div>


@endsection