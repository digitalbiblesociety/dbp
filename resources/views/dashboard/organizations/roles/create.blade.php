@extends('layouts.app')

@section('head')
    <style>
        small {display: block}
        #organization-application {margin-top:20px}
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => 'Join an Organization'])

<form action="/home/organizations/roles" method="POST" class="row" id="organization-application">
    <div class="medium-6 columns centered text-center">
        {{ csrf_field() }}
        <p>By Joining an organization, your developer account will get access to the texts that the organization has access to.</p>
        <small>Even if you select no affiliation you'll still have access to all the creative commons and public domain scriptures</small>
        @include('layouts.partials.datalist', ['name' => 'organizations', 'list' => $organizations])
        <textarea name="notes">Notes for the organization</textarea>
        <input type="submit">
    </div>
</form>

@endsection