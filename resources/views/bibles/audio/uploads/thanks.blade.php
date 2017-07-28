@extends('layouts.app')

@section('content')
    <div class="text-center">
        <h1>Thanks, the upload is queued!</h1>
        <h2>Here's hoping the conversion process is successful...</h2>
        <p>Oh, and by the way... {{ $compliment ?? "Thanks!" }}</p>
    </div>
@endsection