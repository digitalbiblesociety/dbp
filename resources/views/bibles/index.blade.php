@extends('layouts.app')

@section('head')
    <title>{{ trans('wiki.bibles_title') }}</title>
    <meta type="description" property="og:description" content="{{ trans('wiki.bibles_description') }}" />

    @include('layouts.partials.ogp')
    <style>
        .hit {
            height:150px;
            position: relative;
        }

        .active-link .pagination-link{
            background: #fff;
        }

        .hit .subtitle,
        .hit .title {
            text-align: center;
        }

        .hit .subtitle .ais-highlight {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
            display: block;
        }

        .hit time {
            position: absolute;
            bottom:5px;
            right:5px;
        }

        .hit .iso {
            position: absolute;
            bottom:5px;
            left:5px;
        }

    </style>

@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => trans('wiki.bibles_title'),
        'breadcrumbs' => [
            '/'     => 'Home',
            '/wiki' => trans('wiki.overview_title'),
            '#'     => trans('wiki.bibles_title')
        ]
    ])

    <div class="container">
        <algolia-bible-search></algolia-bible-search>
    </div>
@endsection