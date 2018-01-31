@extends('layouts.app')


@section('content')

    <h1>User Notes</h1>

    <div class="row text-center">
        <div class="medium-4 columns">
            Most Popular Bible
            <div class="stat">{{ $notes['most_popular_bible'] }}</div>
        </div>
        <div class="medium-4 columns">
            Total Notes
            <div class="stat">{{ $notes['count'] }}</div>
        </div>
        <div class="medium-4 columns">
            most prolific user
            <div class="stat">{{ $notes['most_prolific_user'] }}</div>
        </div>
    </div>

    <div class="row">
    <div class="medium-4 columns">
        <input />
    </div>
    </div>

@endsection