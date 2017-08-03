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

    <div class="row">
    <h1>// Version 2 Specific Routes</h1>
        <p>Don't care about our awesome new features? Well, we care about you and that's why we're offering continuous support for GET requests on v2. Simply want the benefit of better clean data without updating your code. Well you came to the right place... but before you ignore the new stuff entirely check out our <a href="/features/comparison">Features comparison</a>. We think you'll choose the new version.</p>

        <div class="row">
        <h2>// LANGUAGES</h2>
            <div class="medium-4 columns">
                <div class="panel text-center">
                    <h3>Language Listing <small>library/language</small></h3>
                    <a href="/library/language" class="button supported">Live and Supported</a>
                </div>
            </div>
            <div class="row text-center depreciated">
                <h3>depreciated</h3>
                <div class="medium-3 columns"><span>Language Create <small>library/language</small></span></div>
                <div class="medium-3 columns"><span>Language Update <small>library/language</small></span></div>
            </div>
        </div>

        <div class="row">
            <h2>// VERSIONS</h2>
            <div class="medium-4 columns">
                <div class="panel text-center">
                    <h3>Version Listing <small>library/version</small></h3>
                    <a href="/library/version" class="button static">Static</a>
                </div>
            </div>
            <div class="row text-center depreciated">
                <h3>depreciated</h3>
                <div class="medium-3 columns"><span>Version Create <small>library/version/create</small></span></div>
                <div class="medium-3 columns"><span>Version Update <small>library/version/update</small></span></div>
            </div>
        </div>


    </div>

    // VOLUMES

    // [supported] Volume Listing
    // [omitted] Volume Create
    // [omitted] Volume Update
    // [static] Volume Language List
    // [static] Volume Language Family List
    // [supported] Volume Organization Listing
    // [static] Volume History List
    Route::get('library/volume', 'BiblesController@index');
    // TODO: Volume Create
    // TODO: Volume Update

    Route::get('library/volumelanguage', function () {return json_decode(file_get_contents(public_path('static/volume_language_list.json')));});
    Route::get('library/volumelanguagefamily', function () {return json_decode(file_get_contents(public_path('static/volume_language_family.json')));});
    Route::get('library/volumeorganization', 'OrganizationsController@index')->name('v2_volume_organization_list');
    Route::get('library/volumehistory', function () {return json_decode(file_get_contents(public_path('static/library_volume_history.json')));});



    // BOOKS

    // [supported] Book Order Listing
    // [omitted] Book Order Create
    // [omitted] Book Order Update
    // [omitted] Book Order Delete
    // [] Book Listing
    // [] Book Name Listing
    // [omitted] Book Name Create
    // [omitted] Book Name Update
    Route::get('library/bookorder', 'BooksController@show');
// TODO: Book Order Create
// TODO: Book Order Update
// TODO: Book Order Delete
Route::get('library/book', 'BooksController@show');

// [] Chapter Listing
// [] Verse Info Listing
// [] Numbers Listing
// [] Numbers Create
// [] Numbers Update

// Metadata
// [] Metadata Listing
// [] Metadata Create
// [] Metadata Update

// Organizations
// [] Volume Asset Location
// [] Organization Listing
// [] Organization Create
// [] Organization Modify


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