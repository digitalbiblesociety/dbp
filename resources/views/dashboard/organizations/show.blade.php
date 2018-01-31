@extends('layouts.app')

@section('head')
    <style>
        .dataTables_length {display:none}

        .users .user {
            list-style: none;
            padding:20px;
        }

        .users .user span.requesting-access{
            color:#999;
        }
    </style>
@endsection

@section('content')
    <div class="small-8 columns centered">
        <h1 class="text-center">{{ $organization->currentTranslation->name }}</h1>
        <p class="text-justify">{!! $organization->currentTranslation->description  !!}</p>
    </div>

    <div class="row">
        <div class="medium-3 columns">
            <div class="users">
            @foreach($organization->members as $member)
                <div class="user">
                    <img src="{{ $member->profile }}" />
                    <b>{{ $member->name }}</b>
                    @foreach($member->roles as $role)
                        @if(($role->organization_id == $organization->id))
                            <div class="{{ $role->role }}">{{ $role->role }}</div>
                        @endif
                    @endforeach
                </div>
            @endforeach
            </div>
        </div>


        @if($organization->BiblesCount != 0)
        <div class="medium-12 columns">
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
        @endif

        @if($organization->filesetsCount != 0)
            <div class="medium-12 columns">
                <table class="table bible-list">
                    <thead>
                    <td>Name</td>
                    <td>Vernacular Name</td>
                    <td>Variation id</td>
                    <td>Date</td>
                    <td>ID</td>
                    </thead>
                    <tbody>
                    @foreach($organization->filesets as $fileset)
                        <tr>
                            <td><a href="{{ route('view_bibles.manage', ['id' => $fileset->id ]) }}">{{ $fileset->bible->currentTranslation->name }}</a></td>
                            <td>{{ $fileset->name }}</td>
                            <td>{{ $fileset->variation_id }}</td>
                            <td>{{ $fileset->date }}</td>
                            <td>{{ $fileset->id }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>


@endsection