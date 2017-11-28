@extends('layouts.app')

@section('head')
    <style>
        .dataTables_length {display:none}
    </style>
@endsection

@section('content')

    <div class="small-8 columns centered">
        <h1 class="text-center">{{ $organization->currentTranslation->name }}</h1>
        <p class="text-justify">{{ $organization->currentTranslation->description }}</p>
    </div>

    <div class="row">
        <div class="medium-3 columns">
            <ul class="users">
            @foreach($organization->members as $member)
                <li>
                    <img src="{{ $member->profile }}" />
                    <b>{{ $member->name }}</b>
                    <span>{{ $member->role }}</span>
                    @if($user->role($organization->id)->first()->role == "manager") <a href="">Edit</a> @endif
                </li>
            @endforeach
            </ul>
        </div>
        <div class="medium-9 columns">
            <table class="table bible-list">
                <thead>
                    <td>Name</td>
                    <td>Vernacular Name</td>
                    <td>Variation id</td>
                    <td>Date</td>
                    <td>ID</td>
                </thead>
                <tbody>
                    @foreach($organization->bibles as $bible)
                        <tr>
                            <td><a href="{{ route('view_bibles.manage', ['id' => $bible->id ]) }}">{{ $bible->currentTranslation->name }}</a></td>
                            <td>{{ $bible->vernacularTranslation->first()->name }}</td>
                            <td>{{ $bible->variation_id }}</td>
                            <td>{{ $bible->date }}</td>
                            <td>{{ $bible->id }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection