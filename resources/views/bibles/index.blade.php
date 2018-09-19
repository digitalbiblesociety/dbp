@extends('layouts.app')

@section('head')
    <title>{{ trans('wiki.bibles_title') }}</title>
    <meta type="description" property="og:description" content="{{ trans('wiki.bibles_description') }}" />

    @include('layouts.partials.ogp')

@endsection

@section('content')

    @include('layouts.partials.banner',[
        'title' => trans('wiki.bibles_title'),
        'breadcrumbs' => [
            '/'     => 'Home',
            '/wiki' => trans('wiki.overview_title'),
            '#'     => trans('wiki.bibles_title')
        ]
    ])

    <div class="container">

        <div class="column is-8 is-offset-2">

            <div class="columns">
                <div class="select column is-4">
                    <select>
                        <option>Select dropdown</option>
                        <option>With options</option>
                    </select>
                </div>
                <div class="select column is-4">
                    <select>
                        <option>Select dropdown</option>
                        <option>With options</option>
                    </select>
                </div>
            </div>

                <label><input type="search" class="input is-large is-centered" placeholder="Search Bibles"></label>
            </div>

    </div>
@endsection