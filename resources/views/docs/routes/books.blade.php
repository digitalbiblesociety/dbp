@extends('layouts.app')

@section('head')
    <style>
        .results {
            max-height:400px;
            width:100%;
            overflow: scroll;
            padding:1rem;
            background-color: #f8f8f8;
        }
    </style>
@endsection

@section('content')

    <h1>Books</h1>

    <div class="route-section row">
        <div class="medium-4 columns">
            <h5>{{ trans('docs.books_index_title') }}</h5>
            <code>{{ route('v4_books.index') }}</code>
        </div>
        <div class="results">
<pre><?php
	echo file_get_contents(route('v4_books.index',['key' => 1234,'pretty']));
	?></pre>
        </div>
    </div>

    <div class="route-section row">
        <div class="medium-4 columns">
            <h5>{{ trans('docs.books_show_title') }}</h5>
            <code>{{ route('v4_books.show', 'GN') }}</code>
            <div class="results">

                <?php

                    echo file_get_contents(route('v4_books.show', 'GN'));

                ?>

            </div>
        </div>
        <div class="medium-8 columns">
            <p class="text-center"><b>Description:</b><br>Display the bible meta data for the specified ID.</p>
            <blockquote>
                Display a resource,<br>
                By providing a book id.<br>
                Filter using fields.
            </blockquote>
        </div>
    </div>
@endsection