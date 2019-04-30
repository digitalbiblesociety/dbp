@extends('layouts.app')

@section('head')
    <style>
        .card {
            min-height:200px;
            padding:15px;
            display: block;
        }

        .card h4 {
            color:#222;
        }
    </style>
@endsection

@section('content')



    <div class="container">

        @include('validations.validate-nav')

        <section class="info-tiles mt50">
            <div class="tile is-ancestor has-text-centered">
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ $bibles_sine_connections }}</p>
                        <p class="subtitle">Bibles without Fileset Connections</p>
                    </article>
                </div>
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ \App\Models\Language\Language::select('iso')->distinct()->count() }}</p>
                        <p class="subtitle">Languages</p>
                    </article>
                </div>
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ \App\Models\Bible\Bible::count() }}</p>
                        <p class="subtitle">Total Bibles</p>
                    </article>
                </div>
                <div class="tile is-parent">
                    <article class="tile is-child box">
                        <p class="title">{{ \App\Models\Bible\BibleFileset::count() }}</p>
                        <p class="subtitle">Filesets</p>
                    </article>
                </div>
            </div>
        </section>

        <div class="columns">
            <div class="is-size-6 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">
                        {{ $filesets_sine_bible_files->count() }}
                        Filesets without Files <small>(Ignoring plain text)</small>
                    </h4>
                    @foreach($filesets_sine_bible_files as $bible_file)
                        {{ $bible_file->hash_id }} |
                    @endforeach
                </div>
            </div>

            <div class="is-size-6 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">
                        {{ $filesets_sine_bibleverses->count() }}
                        Filesets without Verses <small>(Only plain text)</small>
                    </h4>
                    @foreach($filesets_sine_bibleverses as $bible_verses)
                        {{ $bible_verses }} |
                    @endforeach
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="is-size-12 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Bibles without Titles:</h4>
                    @foreach($bibles_sine_translations as $bible)
                        {{ $bible->id }} |
                    @endforeach
                </div>
            </div>
        </div>

        <div class="columns">
            <div class="is-size-6 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Bibles without Books:</h4>
                    @foreach($bibles_sine_bookNames as $bible)
                        {{ $bible->id }},
                    @endforeach
                </div>
            </div>

            <div class="is-size-6 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Filesets without Copyrights:</h4>
                    @foreach($filesets_sine_copyrights as $fileset)
                        {{ $fileset->hash_id }}
                    @endforeach
                </div>
            </div>

        </div>

        <div class="columns">

            <div class="is-size-12 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Filesets without Organizations:</h4>
                    @foreach($filesets_sine_organizations as $fileset)
                        {{ $fileset->hash_id }},
                    @endforeach
                </div>
            </div>

        </div>

        <div class="columns">

            <div class="is-size-12 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Filesets without Permissions:</h4>
                    @foreach($filesets_sine_permissions as $fileset)
                        [{{ $fileset->id }} | {{ $fileset->hash_id }}],
                    @endforeach
                </div>
            </div>

        </div>

        <div class="columns">

            <div class="is-size-12 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Filesets without Connections:</h4>
                    @foreach($filesets_sine_connections as $fileset)
                        {{ $fileset->hash_id }},
                    @endforeach
                </div>
            </div>

        </div>

        <div class="columns">

            <div class="is-size-12 column">
                <div class="card mt50">
                    <h4 class="is-size-5 has-text-centered">Filesets without Connections:</h4>
                    @foreach($filesets_sine_connections as $fileset)
                        {{ $fileset->hash_id }},
                    @endforeach
                </div>
            </div>

        </div>

    </div>

@endsection