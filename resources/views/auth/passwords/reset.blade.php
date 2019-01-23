@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => __('Reset Password')
    ])

            <form id="password-reset" class="column is-half is-offset-one-quarter" method="POST" action="{{ route('v4_user.password_reset', ['token' => $reset_request->token,'v' => 4,'key' => 'tighten_37518dau8gb891ub']) }}">
                @csrf
                <div id="form-box" class="box">

                    <div id="message-box"></div>

                    <input type="hidden" name="token_id" value="{{ $reset_request->token }}">
                    <input type="hidden" name="email" value="{{ $reset_request->email ?? '' }}">
                    <div class="field">
                        <label class="label" for="new_password">{{ __('New Password') }}</label>
                        <small>Passwords must be at least eight characters</small>
                        <div class="control"><input class="input is-medium" id="password" type="password" name="new_password" required></div>
                        @if($errors->has('password')) <span class="help is-danger"><strong>{{ $errors->first('password') }}</strong></span> @endif
                    </div>

                    <div class="field">
                        <label class="label" for="password-confirm">{{ __('Confirm Password') }}</label>
                        <div class="control"><input class="input is-medium" id="password-confirm" type="password" name="new_password_confirmation" required></div>
                        @if($errors->has('new_password_confirmation'))<span class="help is-danger"><strong>{{ $errors->first('new_password_confirmation') }}</strong></span>@endif
                    </div>

                    <button type="submit" class="button">{{ __('Reset Password') }}</button>
                </div>
            </form>
        </div>

    </div>

@endsection

@section('footer')
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script>
	    $(document).ready(function () {

	    var form = $('#password-reset');
	    form.submit(function (e) {
		    e.preventDefault();
                var valid = Validate();
                if(valid) {
	                $.ajax({
		                type: form.attr('method'),
		                url:  form.attr('action'),
		                data: form.serialize(),
		                dataType: 'json',
		                success: function (data) {
			                $( "#message-box" ).prepend( '<div class="alert alert-success has-text-centered">Your password has been reset</div>' );
			                window.location.href = "https://live.bible.is";
		                }
	                });
                }
	    });

	    function Validate() {
		    $( "#error-box" ).empty();

		    if ($("#password").val().length < 7) {
			    $( "#message-box" ).prepend( '<div class="alert alert-error has-text-centered">Your password must be at least eight characters</div>' );
			    return false;
		    }

		    if($("#password").val() != $("#password-confirm").val()) {
			    $( "#message-box" ).prepend( '<div class="alert alert-error has-text-centered">Your passwords do not match</div>' );
			    return false;
            }

            return true;
	    }

	    });
    </script>
@endsection
