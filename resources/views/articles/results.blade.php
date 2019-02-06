@extends('layouts.main')

@section('head')
    <title>{{ trans('about.articles_results_page') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('about.articles_results_description') }}" />
    <style>
        article img {
            width: 50px;
            height: 50px;
            float: left;
            margin: 0 15px;
        }
        article .title {
            font-size:1.3rem;
            color:#333;
        }
    </style>
@endsection

@section('body')

    @include('layouts.banner', [
        'title' =>  trans('about.articles_results_title'),
        'subtitle'  => '',
      'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
        'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
        'noGradient' => true,
        'breadcrumbs' => [
            route('dbs_home')           => trans('fields.home'),
            route('dbs_about')          => trans('fields.about'),
            route('dbs_articles.index') => trans('fields.articles'),
            '#' => trans('about.articles_results_title'),
        ]
    ])

    @if(count($articles) == 0)
        <div class="medium-6 columns centered">
            <h2 class="text-center">{{ trans('fields.noResults') }}</h2>
        </div>
    @endif

    @foreach($articles as $slug => $title)
        <article class="small-4 columns">
            <a href="/articles/{{ $slug }}">
                <img src="/img/articles/{{$slug}}.svg">
                <div class="title">{{ $title }}</div>
            </a>
        </article>
    @endforeach

@endsection