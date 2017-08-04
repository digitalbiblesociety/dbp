@extends('layouts.app')

@section('head')
    <style>
        .stat {
            font-size:2rem;
        }
        .stat-block h3 {
            padding:40px 0 0 0;
            margin:0;
            font-weight:normal;
            letter-spacing: 2px;
            text-transform: uppercase;
            color:#FFF;
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

        #banner:after {
            content:"";
            position: absolute;
            left:50%;
            margin-left: -20px;
            bottom:-20px;
            width: 0;
            height: 0;
            border-left: 20px solid transparent;
            border-right: 20px solid transparent;
            border-top: 20px solid #222;
        }

    </style>
@endsection

@section('content')

    <section id="banner">
        <div class="row">
            <h1 class="text-center">Welcome to Koinos</h1>
            <p>Koinos is an open source API that collects, curates and delivers language, country, and bible data. It also serves as a directory of S3 buckets for the largest bible-focused content creators in the world. Including Faith Comes By Hearing, Global Recordings Network, and Wycliffe Bible Translators.</p>
        </div>
        <div class="row stat-block">
            <div class="medium-10 columns centered text-center">
                <div class="small-6 medium-2 columns"><a href="#"><div class="stat">{{ $count['languages'] or 0 }}</div><span>Languages</span></a></div>
                <div class="small-6 medium-2 columns"><a href="#"><div class="stat">{{ $count['countries'] or 0 }}</div><span>Countries</span></a></div>
                <div class="small-6 medium-2 columns"><a href="#"><div class="stat">{{ $count['alphabets'] or 0 }}</div><span>Alphabets</span></a></div>
                <div class="small-6 medium-2 columns"><a href="#"><div class="stat">{{ $count['organizations'] or 0 }}</div><span>Pre-approved</span></a></div>
                <div class="small-6 medium-2 columns"><a href="#"><div class="stat">{{ $count['bibles'] or 0 }}</div><span>Bibles</span></a></div>
                <div class="small-6 medium-2 columns"><a href="#"><div class="stat">{{ $count['resources'] or 0 }}</div><span>Resources</span></a></div>
            </div>
            <div class="row">
                <h3 class="text-center"><em>&</em> Infinite Possibility</h3>
            </div>
        </div>
    </section>

    <div class="row">
        <div class="medium-4 columns text-center">
            <h3>Id3 Tagger and Audio Converter</h3>
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


@endsection