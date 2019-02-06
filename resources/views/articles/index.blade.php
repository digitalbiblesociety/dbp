@extends('layouts.main')

@section('head')

    <title>{{ trans('main.title_articles_page') }} | {{ trans('fields.siteTitle') }}</title>
    <meta name="description" content="{{ trans('main.title_articles_description') }}">

    <style>
        [itemtype="http://schema.org/BreadcrumbList"] span[itemprop="item"], [itemtype="http://schema.org/BreadcrumbList"] a, .search-text, .nav_down, #search-input::placeholder,
        #search-section .language-dropdown > a {color: #333 !important;  font-weight: 600; text-shadow: 1px 0 1px rgba(255, 255, 255, 0.4)  }
        #search-input {border: 1px solid #555;background-color: rgba(255, 255, 255, 0.3) !important;}.nav_down {color:#444!important;}
        section[role=banner] nav[role=tablist], section[role=banner] .banner-heading { background-color: rgba(0, 0, 0, .4);}


/* ==============================
   Blog
   ============================== */

        .blog-item {margin-bottom:80px;position:relative}
        .blog-media img,
        .blog-media .video,
        .blog-media iframe{
            width: 100%;
        }
        .blog-item-title{
            margin: 0 0 .5em 0;
            padding: 0;
            font-size: 24px;

            text-transform: uppercase;

        }
        .blog-item-title a{
            color: #111;
            text-decoration: none;
        }
        .blog-item-title a:hover{
            color: #777;
            text-decoration: none;
        }
        .blog-item-data{
            margin-bottom: 30px;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 1px;
            color: #aaa;
        }
        .separator{
            margin: 0 5px;
        }
        .blog-item-data a{
            color: #aaa;
            text-decoration: none;

            transition: all 0.27s cubic-bezier(0.000, 0.000, 0.580, 1.000);
        }
        .blog-item-data a:hover{
            color: #000;
            text-decoration: none;
        }
        .blog-item-body{
            font-size: 16px;
            font-weight: 300;
            color: #5f5f5f;
            line-height: 1.8;
        }
        .blog-item-body h1,
        .blog-item-body h2,
        .blog-item-body h3,
        .blog-item-body h4,
        .blog-item-body h5,
        .blog-item-body h6{
            margin: 1.3em 0 0.5em 0;
        }

        .blog-item-q p{
            position: relative;
            background: #f8f8f8;
            padding: 17px 20px;
            font-size: 18px;
            font-weight: 300;
            font-style: normal;
            letter-spacing: 0;
        }
        .blog-item-q p a{
            color: #555;
            text-decoration: none;

            transition: all 0.27s cubic-bezier(0.000, 0.000, 0.580, 1.000);
        }
        .blog-item-q:hover a,
        .blog-item-q p a:hover{
            text-decoration: none;
            color: #777;
        }
        .blog-item-q p:before,
        .blog-item-q p:after{

            transition: all 0.27s cubic-bezier(0.000, 0.000, 0.580, 1.000);
        }
        .blog-item-q p:before{
            content: '"';
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .blog-item-q p:after{
            content: '"';
        }

        .blog-item-q p:hover:before,
        .blog-item-q p:hover:after{
            color: #777;
        }


        /*
         * Sidebar
         */

        .sidebar{
            margin-top: 10px;
        }
        .widget{
            margin-bottom: 60px;
        }
        .widget .img-left{
            margin: 0 10px 10px 0;
        }
        .widget .img-right{
            margin: 0 0 10px 10px;
        }
        .widget-title{
            margin-bottom: 20px;
            padding-bottom: 5px;
            text-transform: uppercase;
            font-size: 14px;

            color: #777;
            border-bottom: 1px solid #ccc;
        }
        .widget-body{
            font-size: 13px;
            color: #777;
        }
        .widget-text{
            line-height: 1.7;
        }


        /* Search widget */

        .search-wrap{
            position: relative;
        }
        .search-field{
            width: 100% !important;
            height: 40px !important;
        }
        .search-button{
            width: 42px;
            height: 40px;
            line-height: 38px;
            margin-top: -20px;
            position: absolute;
            top: 50%;
            right: 1px;
            overflow: hidden;
            background: #000;
            border: none;
            outline: none;
            color: #999;
            font-size: 14px;
        }
        body[dir="rtl"] .search-button {
            right: auto;
            left:0;
        }
        .search-button svg {
            margin-top:5px;
        }

        .search-button:hover{
            color: #111;
        }
        .search-button:hover + .search-field{
            border-color: #ccc;
        }


        /* Widget menu */

        .widget-menu{
            font-size: 13px;
        }
        .widget-menu li{
            padding: 5px 0;

        }
        .widget-menu li a{
            color: #555;
            text-decoration: none;
            padding-bottom: 2px;
            text-decoration: none;
            border-bottom: 1px solid transparent;
            transition: all 0.17s cubic-bezier(0.000, 0.000, 0.580, 1.000);
        }
        .widget-menu li a:hover,
        .widget-menu li a.active{
            color: #999;
        }
        .widget-menu li small{
            font-size: 11px;
            color: #aaa;
        }


        /* Widget tags */

        .tags{

        }
        .tags a{
            display: inline-block;
            margin: 0 2px 5px 0;
            padding: 5px 7px;
            border: 1px solid #e9e9e9;
            color: #777;
            font-size: 11px;
            text-transform: uppercase;
            text-decoration: none;
            letter-spacing: 1px;
            transition: all 0.27s cubic-bezier(0.000, 0.000, 0.580, 1.000);
        }
        .tags a:hover{
            text-decoration: none;
            border-color: #333;
            color: #111;
        }

        /* Posts */
        .widget-posts{
            color: #aaa;
        }
        .widget-posts li{
            font-size: 12px;
            padding: 12px 0;
            border-bottom: 1px dotted #eaeaea;
        }
        .widget-posts li a{
            font-size: 13px;
            display: block;
            color: #555;
            text-decoration: none;
            transition: all 0.17s cubic-bezier(0.000, 0.000, 0.580, 1.000);
        }
        .widget-posts li a:hover{
            color: #999;
        }
        .widget-posts-img{
            float: left;
            margin: 0 7px 0 0;
        }
        .widget-posts-descr{
            overflow: hidden;
        }
    </style>
@endsection

@section('body')

    @include('layouts.banner', [
        'title'              => trans('fields.articles_news'),
        'subtitle'           => trans('about.articles_subtitle'),
        'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
        'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
        'noGradient' => true,
        'breadcrumbs' => [
            route('dbs_home')   => trans('fields.home'),
            route('dbs_about')  => trans('fields.about'),
            '#'                 => trans('fields.articles')
        ]
    ])

    <div class="page-section">
        <div class="container relative">
        <div class="row">

            <div class="small-hide medium-3 columns">

                <!-- Search Widget -->
                <div class="widget">
                    <form class="form-inline form" role="form" action="/article/search">
                        <div class="search-wrap">
                            <button class="search-button animate" type="submit" title="Start Search">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24" height="30px" width="30px"><path fill="#FFF" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                            </button>
                            <input type="search" name="search" class="form-control search-field" placeholder="{{ trans('about.articles_search') }}...">
                        </div>
                    </form>
                </div>

                <!-- Widget -->
                <div class="widget">

                    <h5 class="widget-title font-alt">{{ trans('about.articles_tags') }}</h5>

                    <div class="widget-body">
                        <div class="tags">
                            @foreach($tags as $tag)
                                <a href="{{ route('dbs_articles.tag',['tag' => $tag->id]) }}">{{ $tag->name }}</a>
                            @endforeach
                        </div>
                    </div>

                </div>
                <!-- End Widget -->

                <!-- Widget -->
                <div class="widget">

                    <h5 class="widget-title font-alt">{{ trans('about.articles_recent') }}</h5>

                    <div class="widget-body">
                        <ul class="clearlist widget-posts">
                            @foreach($articles->take(3) as $article)
                            <li>
                                <a href="/articles/{{ $article->id }}"><img width="50px" height="50px" src="/img/articles/{{ $article->id }}.svg" alt="" class="widget-posts-img"></a>
                                <div class="widget-posts-descr">
                                    <a href="#" title="">{{ $article->title }}</a>
                                    {{ trans('about.articles_postedBy') }} {{ $article->user->name }} {{ $article->created_at->diffforhumans() }}
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>
                <div class="medium-8 columns">
                    @foreach ($articles as $article)
                        <div class="blog-item">
                            <h2 class="blog-item-title font-alt"><a href="/articles/{{ $article->id }}">{{ $article->title }}</a></h2>

                            <!-- Author, Categories, Comments -->
                            <div class="blog-item-data">
                                <time datetime="{{ $article->created_at }}"> {{ $article->created_at->diffforhumans() }}</time>
                                <span class="separator">&nbsp;</span>
                                <a href="{{ route('dbs_articles.user', ['user' => $article->user->id]) }}"> {{ ($article->hasTag('newsletter')) ? "Digital Bible Society" : $article->user->name }}</a>
                                <span class="separator">&nbsp;</span>
                                <i class="fa fa-folder-open"></i>
                                @foreach($article->tags as $tag)
                                <a href="{{ route('dbs_articles.tag', ['tag' => $tag->id]) }}">{{ $tag->name }}</a>@if(!$loop->last),@endif
                                @endforeach
                            </div>

                            <!-- Text Intro -->
                            <div class="blog-item-body">
                                <p>{{ $article->description }}</p>
                            </div>

                            <!-- Read More Link -->
                            <div class="blog-item-foot row">
                                <div class="small-4 columns"><a href="/articles/{{ $article->id }}" class="button">Read More</a></div>
                            </div>

                        </div>
                    @endforeach
                </div>

        </div>
        </div>

    </div>
@endsection