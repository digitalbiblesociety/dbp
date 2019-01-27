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

        <form method="POST" action="{{ route('profile.update', $user->id) }}" class="form-horizontal" role="form" enctype=”multipart/form-data”>
            {{ csrf_field() }}
            {{ method_field('PATCH') }}

            <div class="columns">

                <div class="field column">
                    <label class="label">Username</label>
                    <div class="control"><input class="input @if($errors->has('name')) is-danger @endif" type="text" placeholder="Name" value="{{ $user->name ?? old('name') }}"></div>
                    @if($errors->has('name')) <p class="help is-danger">{{ $errors->first('name') }}</p> @endif
                </div>

                <div class="field column">
                    <label class="label">Avatar</label>
                    <div class="control"><input class="input @if($errors->has('avatar')) is-danger @endif" type="file" placeholder="Avatar" value="{{ $user->avatar ?? old('avatar') }}"></div>
                    @if($errors->has('avatar')) <p class="help is-danger">{{ $errors->first('avatar') }}</p> @endif
                </div>

                <div class="field column">
                    <label class="label">Location</label>
                    <div id="locationField">
                        <input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text"/>
                    </div>
                    @if($errors->has('location')) <p class="help is-danger">{{ $errors->first('location') }}</p> @endif


                    <table id="address">
                        <tr>
                            <td class="label">Street address</td>
                            <td class="slimField"><input class="field" id="street_number"
                                                         disabled="true"/></td>
                            <td class="wideField" colspan="2"><input class="field" id="route"
                                                                     disabled="true"/></td>
                        </tr>
                        <tr>
                            <td class="label">City</td>
                            <td class="wideField" colspan="3"><input class="field" id="locality"
                                                                     disabled="true"/></td>
                        </tr>
                        <tr>
                            <td class="label">State</td>
                            <td class="slimField"><input class="field"
                                                         id="administrative_area_level_1" disabled="true"/></td>
                            <td class="label">Zip code</td>
                            <td class="wideField"><input class="field" id="postal_code"
                                                         disabled="true"/></td>
                        </tr>
                        <tr>
                            <td class="label">Country</td>
                            <td class="wideField" colspan="3"><input class="field"
                                                                     id="country" disabled="true"/></td>
                        </tr>
                    </table>

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

@section('footer')

    <script>
	    // This example displays an address form, using the autocomplete feature
	    // of the Google Places API to help users fill in the information.

	    // This example requires the Places library. Include the libraries=places
	    // parameter when you first load the API. For example:
	    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

	    var placeSearch, autocomplete;
	    var componentForm = {
		    street_number: 'short_name',
		    route: 'long_name',
		    locality: 'long_name',
		    administrative_area_level_1: 'short_name',
		    country: 'long_name',
		    postal_code: 'short_name'
	    };

	    function initAutocomplete() {
		    // Create the autocomplete object, restricting the search to geographical
		    // location types.
		    autocomplete = new google.maps.places.Autocomplete(
			    /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
			    {types: ['geocode']});

		    // When the user selects an address from the dropdown, populate the address
		    // fields in the form.
		    autocomplete.addListener('place_changed', fillInAddress);
	    }

	    function fillInAddress() {
		    // Get the place details from the autocomplete object.
		    var place = autocomplete.getPlace();

		    for (var component in componentForm) {
			    document.getElementById(component).value = '';
			    document.getElementById(component).disabled = false;
		    }

		    // Get each component of the address from the place details
		    // and fill the corresponding field on the form.
		    for (var i = 0; i < place.address_components.length; i++) {
			    var addressType = place.address_components[i].types[0];
			    if (componentForm[addressType]) {
				    var val = place.address_components[i][componentForm[addressType]];
				    document.getElementById(addressType).value = val;
			    }
		    }
	    }

	    // Bias the autocomplete object to the user's geographical location,
	    // as supplied by the browser's 'navigator.geolocation' object.
	    function geolocate() {
		    if (navigator.geolocation) {
			    navigator.geolocation.getCurrentPosition(function(position) {
				    var geolocation = {
					    lat: position.coords.latitude,
					    lng: position.coords.longitude
				    };
				    var circle = new google.maps.Circle({
					    center: geolocation,
					    radius: position.coords.accuracy
				    });
				    autocomplete.setBounds(circle.getBounds());
			    });
		    }
	    }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBJoJx8R2l3-q-rSDwijGLSQJqLfKhy_-w&libraries=places&callback=initAutocomplete"
            async defer></script>
@endsection