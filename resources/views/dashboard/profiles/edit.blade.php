@extends('layouts.app')

@section('template_title')
    {{ trans('profile.templateTitle') }}
@endsection

@section('head')
    <style>
        #address {
            display: none;
        }
    </style>
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

        <form method="POST" action="{{ route('profile.update', $user->id) }}" class="form-horizontal" role="form" enctype=”multipart/form-data”>
            {{ csrf_field() }}
            {{ method_field('PUT') }}

            <div class="columns">

                <div class="field column">
                    <label class="label">Username</label>
                    <div class="control"><input class="input @if($errors->has('name')) is-danger @endif" type="text" name="name" placeholder="Name" value="{{ $user->name ?? old('name') }}"></div>
                    @if($errors->has('name')) <p class="help is-danger">{{ $errors->first('name') }}</p> @endif
                </div>

                <div class="field column">
                    <label class="label" for="avatar">Avatar</label>
                    <div class="file has-name is-fullwidth">
                        <label class="file-label">
                            <input class="file-input" type="file" name="resume">
                            <span class="file-cta">
      <span class="file-icon">
        <i class="fas fa-upload"></i>
      </span>
      <span class="file-label">
        Choose a file…
      </span>
    </span>
                            <span class="file-name">
      For your avatar
    </span>
                        </label>
                    </div>
                    {{--

                    <div class="control"><input class="input @if($errors->has('avatar')) is-danger @endif" type="file" name="avatar" placeholder="Avatar" value="{{ $user->avatar ?? old('avatar') }}"></div>
                    @if($errors->has('avatar')) <p class="help is-danger">{{ $errors->first('avatar') }}</p> @endif
                    --}}
                </div>

                <div class="field column">
                    <label class="label">Location</label>
                    <div id="locationField">
                        <input id="autocomplete" class="input" placeholder="Enter your address" onFocus="geolocate()" type="text"/>
                    </div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('location') }}</p> @endif

                    <table id="address">
                        <tr>
                            <td class="label">Street address</td>
                            <td class="slimField"><input class="field" id="street_number" disabled="true"/></td>
                            <td class="wideField" colspan="2"><input class="field" id="route" disabled="true"/></td>
                        </tr>
                        <tr>
                            <td class="label">City</td>
                            <td class="wideField" colspan="3"><input class="field" id="locality" disabled="true"/></td>
                        </tr>
                        <tr>
                            <td class="label">State</td>
                            <td class="slimField"><input class="field" id="administrative_area_level_1" disabled="true"/></td>
                            <td class="label">Zip code</td>
                            <td class="wideField"><input class="field" id="postal_code" disabled="true"/></td>
                        </tr>
                        <tr>
                            <td class="label">Country</td>
                            <td class="wideField" colspan="3"><input class="field" id="country" disabled="true"/></td>
                        </tr>
                    </table>

                </div>
            </div>
            <div class="columns">
                <div class="field column">
                    <label class="label">Email</label>
                    <div class="control"><input class="input @if($errors->has('email')) is-danger @endif" type="text" placeholder="Email" name="email" value="{{ $user->email ?? old('email') }}"></div>
                    @if($errors->has('email')) <p class="help is-danger">{{ $errors->first('email') }}</p> @endif
                </div>
                <div class="field column">
                    <label class="label">First Name</label>
                    <div class="control"><input class="input @if($errors->has('first_name')) is-danger @endif" type="text" placeholder="First Name" name="first_name" value="{{ $user->first_name ?? old('first_name') }}"></div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('first_name') }}</p> @endif
                </div>
                <div class="field column">
                    <label class="label">Last Name</label>
                    <div class="control"><input class="input @if($errors->has('last_name')) is-danger @endif" type="text" placeholder="Last Name" name="last_name" value="{{ $user->last_name ?? old('last_name') }}"></div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('last_name') }}</p> @endif
                </div>
            </div>
            <div class="columns">
                <div class="field column">
                    <label class="label">Notes</label>
                    <div class="control"><textarea name="notes" class="textarea">{{ $user->notes ?? old('notes') }}</textarea></div>
                    @if($errors->has('notes')) <p class="help is-danger">{{ $errors->first('notes') }}</p> @endif
                </div>
            </div>
            <input class="button is-primary" type="submit">
        </form>
    </div>
@endsection

@section('footer')

@endsection