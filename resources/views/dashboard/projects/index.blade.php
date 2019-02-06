@extends('layouts.app')

@section('head')
    <style>
        #create-new-project,
        .card {
            margin-top:15px;
        }
    </style>
@endsection

@section('content')

    <div class="container">

        <section id="create-new-project" class="box columns">
            <div class="column is-offset-4 is-4"><a class="button is-primary is-fullwidth" href="{{ route('dashboard.projects.create') }}">Create a new Project</a></div>
        </section>

        <section class="row columns">
            @foreach($projects as $project)
                <div class="column is-one-third">
                    <div class="card large">
                        <div class="card-image">
                            <figure class="image">
                                @if($project->url_avatar)
                                    <img src="{{ $project->url_avatar }}" alt="Image">
                                @endif
                            </figure>
                        </div>
                        <div class="card-content">
                            <div class="media">
                                <div class="media-left">
                                    <figure class="image is-96x96">
                                        @if($project->url_avatar_icon)
                                            <img src="{{ $project->url_avatar_icon }}" alt="Image">
                                        @endif
                                    </figure>
                                </div>
                                <div class="media-content">
                                    <p class="title is-5 no-padding">{{ $project->name }}</p>
                                    <span class="members is-6"><b>Members:</b> {{ number_format($project->members_count) }}</span>
                                </div>
                            </div>
                            <div class="content">
                                {{ $project->description }}
                                <div class="background-icon"><span class="icon-twitter"></span></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a class="card-footer-item" href="{{ route('dashboard.projects.edit', ['project_id' => $project->id]) }}">Edit</a>
                            <a class="card-footer-item" href="{{ route('dashboard.projects.members', ['project_id' => $project->id]) }}">Members</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </section>

    </div>

@endsection