@extends('layouts.app')

@section('head')
    <style>
        .stat {
            font-size:1rem;
        }
        .stat a {
            color:#666;
        }

        .stat:before {
            content: attr(data-stat);
            display: block;
            font-size:2rem
        }
        #banner {
            padding:3rem 1rem;
            margin-bottom:3rem;
            background: #222;
            color:#f8f8f8;
        }
        #banner p {
            text-align: justify;
            max-width:500px;
            margin:2rem auto;
        }
    </style>
@endsection

@section('content')

    <section id="banner" class="text-center">
        <h1 class="text-center">{{ trans('docs.title') }}</h1>
        <p>{{ trans('docs.description') }}</p>
        <div class="row stat-block">
            <a class="small-6 medium-2 columns stat" href="/languages" data-stat="{{ $count['languages'] or 0 }}">{{ trans('docs.languages') }}</a>
            <a class="small-6 medium-2 columns stat" href="/countries" data-stat="{{ $count['countries'] or 0 }}">{{ trans('docs.countries') }}</a>
            <a class="small-6 medium-2 columns stat" href="/alphabets" data-stat="{{ $count['alphabets'] or 0 }}">{{ trans('docs.alphabets') }}</a>
            <a class="small-6 medium-2 columns stat" href="/organizations" data-stat="{{ $count['organizations'] or 0 }}">{{ trans('docs.preApproved') }}</a>
            <a class="small-6 medium-2 columns stat" href="/bibles" data-stat="{{ $count['bibles'] or 0 }}">{{ trans('docs.bibles') }}</a>
            <a class="small-6 medium-2 columns stat" href="/resources" data-stat="{{ $count['resources'] or 0 }}">{{ trans('docs.resources') }}</a>
        </div>
    </section>

    <div class="row">
        <div class="medium-4 columns text-center">
            <h3>Id3/Audio Converter</h3>
            <p>Automatic Intelligent Meta-data Insertion, available for everyone? We'll see</p>
            <a class="button" href="/bibles/audio/uploads/create">Check it out</a>
        </div>
        <div class="medium-4 columns text-center">
            <h3>Documentation</h3>
            <p>Clear, Concise and simple explanations of each endpoint</p>
            <a class="button" href="/docs/progress">Learn More</a>
        </div>
        <div class="medium-4 columns text-center">
            <h3>oAuth Login System</h3>
            <p>The burden of security passed on to the google, github, or bitbucket</p>
            <a class="button" href="/login">Login or Sign up</a>
        </div>
    </div>

    <div class="row">
        <div class="columns">
        <h2>Features:</h2>
        <ul>
            <li>User Authentication via OAUTH using Google, GitHub and Bitbucket</li>
            <li>User management system for GUI management of rights and permissions to files</li>
            <li>Documentation with API Playground</li>
            <li>Rich Metadata
                <ul>
                    <li>Merge Ethnologue and Glottologue systems</li>
                    <li>Pair Languages and Alphabets</li>
                    <li>Collect a collection of working fonts for languages that are missing</li>
                    <li>Query language by country</li>
                    <li>Vernacular Translations for books and Bible titles</li>
                </ul>
            </li>
        </ul>
        <h2>Meta:</h2>
        <ul>
            <li>Statement of intent and solidarity</li>
        </ul>
        </div>
    </div>


@endsection