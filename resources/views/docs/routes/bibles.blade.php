@extends('layouts.app')

@section('head')
    <style>

        .route-section {
            padding:1rem 0;
            margin:1rem 0;
        }

        .route-section h5 {

        }

        .route-section:nth-child(even) {
            background-color: #f8f8f8;
        }
    </style>
@endsection

@section('content')

    <h1>Bibles</h1>

    <hr />

        <div class="route-section row">
            <div class="medium-4 columns">
                <h5>{{ trans('docs.bibles_index_title') }}</h5>
                <code>{{ route('v4_bibles.index') }}</code>
            </div>
        </div>

        <div class="route-section row">
            <div class="medium-4 columns">
                <h5>{{ trans('docs.bibles_show_title') }}</h5>
                <code>{{ route('v4_bibles.show', 'ENGKJV') }}</code>
            </div>
            <div class="medium-8 columns">
                <p class="text-center"><b>Description:</b><br>Display the bible meta data for the specified ID.</p>
                <blockquote>
                    Display a resource,<br>
                    By providing a bible id.<br>
                    Filter using fields.
                </blockquote>
            </div>
        </div>


@endsection