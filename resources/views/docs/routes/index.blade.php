@extends('layouts.app')

@section('head')
    <style>
        .docs {
            padding:20px;
        }
    </style>
@endsection

@section('content')
    <div class="row docs">
    <h1>Docs (Library Catalog)</h1>
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


    Version Listing
    Version Create
    Version Update

    Volume Listing
    Volume Create
    Volume Update

    Volume Language List
    Volume Language Family List
    Volume Organization Listing
    Volume History List

    Book Order Listing
    Book Order Create
    Book Order Update
    Book Order Delete

    Book Listing
    Book Name Listing
    Book Name Create
    Book Name Update

    Chapter Listing
    Verse Info Listing
    Numbers Listing
    Numbers Create
    Numbers Update
    Metadata Listing
    Metadata Create
    Metadata Update
    Volume Asset Location
    Organization Listing
    Organization Create
    Organization Modify

@endsection