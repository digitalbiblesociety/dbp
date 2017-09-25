@extends('layouts.app')

@section('head')
    <style>
        small {display: block}
        #organization-application {
            margin-top:10%;
        }
    </style>
@endsection

@section('content')

<form action="/home/organizations/roles" method="POST" class="row" id="organization-application">
    <div class="medium-6 columns centered text-center">
        {{ csrf_field() }}
        <p>By Joining an organization, your developer account will get access to the texts that the organization has access to.</p>
        <small>Even if you select no affiliation you'll still have access to all the creative commons and public domain scriptures</small>
        <input type="text" name="organization_id" list="organizations">
        <datalist id="organizations">
            @foreach($organizations as $organization)
                @if($organization->translations("eng")->first())
                    <option value="{{ $organization->id }}">{{ $organization->translations("eng")->first()->name }}</option>
                @endif
            @endforeach
        </datalist>
        <textarea name="notes">Notes for the organization</textarea>
        <input type="submit">
    </div>
</form>

@endsection