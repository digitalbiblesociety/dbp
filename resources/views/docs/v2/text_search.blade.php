@extends('layouts.app')

@section('content')
    <div class="medium-6 columns centered text-center">
        <h1>Search</h1>
        <small>REST URL: http://dbt.io/text/search</small>
        <small>HTTP Method: GET</small>
        <p>This method allows the caller to perform a full-text search within the text of a volume. If the volume has a complementary testament, the search will be performed over both testaments with the results ordered in Bible book order.</p>
    </div>
@endsection