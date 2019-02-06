@extends('layouts.main')

@section('head')
    <title>{{ $user->name }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ $user->description }}" />
    <style>

        article {
            border:thin solid #ccc;
            background: #f8f8f8;
            padding:10px;
            margin:10px;
        }

        article img {
            width:100px;
            margin:0 auto;
            display: block;
        }
    </style>
@endsection

@section('body')

@include('layouts.banner', [
    'title' => $user->name,
    'subtitle'  => $user->description,
    'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
    'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
    'noGradient' => true,
    'breadcrumbs' => [
        route('dbs_home')           => trans('fields.home'),
        route('dbs_articles.index') => trans('fields.articles'),
        '#'                         => $user->name,
    ]
])


<div class="row">
    @foreach($articles as $article)
        <div class="medium-4 columns">
            <article class="text-center">
            <img src="/img/articles/{{ $article->id }}.svg" alt="">
                <a href="{{ route('dbs_articles.show',['slug' => $article->slug]) }}" title="">{{ $article->title }}</a><br>
                <small>{{ $article->created_at->diffforhumans() }}</small>
            </article>
        </div>
    @endforeach
</div>

@endsection