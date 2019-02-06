@extends('layouts.main')

@section('head')

    <title>{{ $article->title }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ $article->description_short }}" />

    <!-- Open Graph Protocol Data -->
    <meta property="fb:app_id" content="325541697785322" />
    <meta property="og:type" content="article" />
    <meta property="og:url" content="{{ url('/articles/'.$article->id.'/') }}" />
    <meta property="og:site_name" content="Digital Bible Society" />
    <meta property="og:image" content="{{ url('/img/articles/'.$article->id.'.jpg') }}" />
    <meta property="og:title" content="{{ $article->title }}" />
    <meta property="og:description" content="{{ $article->description_short }}" />
    <meta property="article:author" content="{{ $article->user->name }}" />
@foreach($article->tags as $tag)
    <meta property="article:tag" content="{{ $tag->name }}" />
@endforeach
    <meta property="article:section" content="{{ $article->catagory }}" />
    <meta property="article:publisher" content="https://www.facebook.com/digitalbiblesociety" />
    <meta property="article:published_time" content="{{ $article->created_at }}" />
    <meta property="article:modified_time" content="{{ $article->updated_at }}" />

    <!-- Twitter Data -->
    <meta name="twitter:card" content="summary" />
    <meta property="twitter:title" content="{{ $article->title }}" />
    <meta property="twitter:description" content="{{ $article->description_short }}" />
    <meta property="twitter:url" content="{{ url('/articles/'.$article->id.'/') }}" />
    <meta property="twitter:image" content="{{ url('/img/articles/'.$article->id.'.jpg') }}"/>

    <style>
        article {
            font-size: 1.25rem;
            line-height: 1.6;
            text-align: justify;
        }
    </style>
@endsection

@section('body')

    @include('layouts.banner', [
        'title'           => $article->title,
        'subtitle'        => $article->subtitle,
      'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
        'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
        'noGradient' => true,
        'breadcrumbs' => [
            route('dbs_home')            => trans('fields.home'),
            route('dbs_articles.index')  => trans('fields.articles'),
            '#'                          => $article->title,
        ]
    ])
    <div itemscope itemtype="http://schema.org/Article">
        <meta itemprop="name" content="{{ $article->title }}" />
        <meta itemprop="author" content="{{ $article->user->name }}" />
        <meta itemprop="wordCount" content="{{ str_word_count(strip_tags($article->body)) }}" />
        <meta itemprop="accessMode" content="textual" />
        {{--
        <div itemprop="interactionStatistic" itemscope itemtype="http://schema.org/InteractionCounter">
            <div itemprop="interactionService" itemscope itemid="http://www.twitter.com" itemtype="http://schema.org/Website">
                <meta itemprop="name" content="Twitter" />
            </div>
            <meta itemprop="interactionType" content="http://schema.org/ShareAction"/>
            <meta itemprop="userInteractionCount" content="1203" />
        </div>
        <div itemprop="interactionStatistic" itemscope itemtype="http://schema.org/InteractionCounter">
            <meta itemprop="interactionType" content="http://schema.org/CommentAction"/>
            <meta itemprop="userInteractionCount" content="78" />
        </div>
        --}}
    <article class="medium-8 columns centered" itemprop="articleBody">
        {!! $article->body !!}
    </article>
    </div>
@endsection