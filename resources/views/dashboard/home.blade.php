@extends('layouts.app')

@section('head')
    <title>{{ $user->name }}'s' Homepage</title>
    <style>
        .disabled {opacity:.2}
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => 'Dashboard'])

    <div class="container box">
        <div class="columns">

            <aside class="menu column is-one-quarter">
                <p class="menu-label">General</p>
                <ul class="menu-list">
                    <li><a href="#" class="disabled">Profile</a></li>
                    <li><a href="#" class="disabled">Accounts</a></li>
                    <li><a href="#" class="disabled">Donate</a></li>
                </ul>
                <p class="menu-label">Messages</p>
                <ul class="menu-list">
                    <li><a href="#" class="disabled">Messages</a></li>
                </ul>
                <p class="menu-label">Notes</p>
                <ul class="menu-list">
                    <li><a href="#" class="disabled">Create New Note</a></li>
                    <li><a href="#" class="disabled">Notes</a></li>
                </ul>
            </aside>

            <section class="column is-three-quarters">
                @if($user->keys->count() !== 0)
                <h2>Keys</h2>
                    @foreach($user->keys as $key_record)
                        <pre>{{ $key_record->key }}</pre>
                        <b>{{$key_record->name }}</b>
                        <p>{{ $key_record->description }}</p>
                    @endforeach
                @endif

                <h2 class="is-size-4 has-text-centered">Projects</h2>
                <hr />
                @if($user->projects->count() == 0)
                    {{ trans('dashboard.no_projects') }}
                @else
                    @foreach($user->projects as $project)
                        <div class="column is-6-desktop">
                            <article class="media">
                                <figure class="media-left">
                                    <p class="image is-64x64"><img src="https://bulma.io/images/placeholders/128x128.png"></p>
                                </figure>
                                <div class="media-content">
                                    <div class="content">
                                        <p><strong>{{ $project->name }}</strong> <small>{{ $project->role }}</small> <br> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.</p>
                                    </div>
                                    <nav class="level is-mobile">
                                        <div class="level-left">
                                            <a class="level-item" href="{{ $project->url_site }}">Site</a>
                                            <a class="level-item" href="#"></a>
                                            <a class="level-item" href="#"></a>
                                        </div>
                                    </nav>
                                </div>
                            </article>
                        </div>
                    @endforeach
                @endif
            </section>

        </div>
    </div>

@endsection