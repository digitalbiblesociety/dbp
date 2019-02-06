@extends('layouts.main')

@section('head')
    <title>{{ $tag->name }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ $tag->description }}" />
@endsection

@section('body')

    @include('layouts.banner', [
        'title' => $tag->name,
        'subtitle'  => $tag->description,
        'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
        'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
        'noGradient' => true,
        'breadcrumbs' => [
            route('dbs_home')           => trans('fields.home'),
            route('dbs_articles.index') => trans('fields.articles'),
            '#'                         => $tag->title,
        ]
    ])

    <section>
            <ul>
                @foreach($articles as $article)
                    <li>
                        <a href=""><img src="/images/blog/previews/post-prev-1.jpg" alt="" class="widget-posts-img"></a>
                        <div class="widget-posts-descr">
                            <a href="#" title="">{{ $article->title }}</a> {{ $article->created_at->diffforhumans() }}
                        </div>
                    </li>
                @endforeach
            </ul>
    </section>
@endsection