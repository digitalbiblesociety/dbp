@extends('layouts.app')

@section('head')
    <style>
        small {
            display: block;
        }

        .docs {
            padding:20px;
        }
        .supported {
            background-color:darkgreen;
        }
    </style>
@endsection

@section('content')
    <div class="row docs">
    <h1>Docs (Library Catalog)</h1>

        <a href="{{ route('swagger', ['version' => 4]) }}">Swagger Spec v4</a>
        <a href="{{ route('swagger', ['version' => 4]) }}">Swagger Spec v2</a>

        <div class="medium-3 columns centered">
        <a class="button" href="/docs/progress">Progress</a></div>
    <ul>
        <li><a href="{{ route('docs_bibles') }}">Bibles</a>
            <ul>
                <li><a href="{{ route('docs_bible_books') }}">Bible Books</a></li>
                <li><a href="{{ route('docs_bible_equivalents') }}">Bible Equivalents Table</a></li>
            </ul>
        </li>
        <li><a href="{{ route('docs_languages') }}">Languages <span>Language Listing</span></a></li>
        <li><a href="{{ route('docs_language_create') }}">Language Create</a></li>
        <li><a href="{{ route('docs_language_update') }}">Language Update</a></li>
            <ul>
                <li><a href="{{ route('docs_alphabets') }}">Alphabets & fonts</a></li>
            </ul>


        <li><a href="{{ route('docs_countries') }}">Countries</a></li>
    </ul>
    </div>

@endsection