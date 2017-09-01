@extends('layouts.app')

@section('content')

    <h1>Country Language Listing</h1>
    <small><b>REST URL:</b> http://dbt.io/country/countrylang</small>
    <small><b>HTTP Method:</b> GET</small>

    <h3>Service Description</h3>
    <p>This method retrieves country language information. Filter languages by a specified country code or filter countries by specified language code. Country flags can also be retrieved by requesting one of the permitted image sizes. Languages can be sorted by the country code (default) and the language code.</p>

@endsection