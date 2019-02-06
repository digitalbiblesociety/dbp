@extends('layouts.main')

@section('head')
    <title>Edit | {{ $article->title }}</title>
    <link href="/css/selectize.css" rel="stylesheet" />
    <style>
        .delete {
            display: none;
        }

        .delete form {
            background-image:url('/img/art_custom/delete.png');
            background-repeat: no-repeat;
            background-color:#FFF;
            background-size:cover;
            width:500px;
            height:500px;
            display: block;
            position: fixed;
            top:50%;
            left:50%;
            margin:-250px 0 0 -250px;
            z-index: 99999;
        }

        .delete .overlay {
            content:"";
            background-color:rgba(0,0,0,.75);
            position: fixed;
            top:0;
            bottom: 0;
            left:0;
            right:0;
            display: block;
            z-index: 99998;
        }
    </style>
@endsection

@section('body')

@include('layouts.banner', [
    'title' => "Edit ".$article->title,
    'subtitle'  => "Fix Typos, add information",
      'blurryImage' => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
      'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
      'noGradient' => true,
    'breadcrumbs' => [
        route('dbs_home') => trans('fields.home'),
        route('dbs_about') => trans('fields.about'),
        route('dbs_articles.index') => trans('fields.articles'),
        '#' => trans('fields.edit'),
    ]
])

<form action="/articles/{{ $article->id }}" method="POST">
    <input type="hidden" name="_method" value="PUT">
    @include('articles.form')
</form>
<div class="delete">
    <div class="overlay"></div>
    <form action="/articles/{{$article->id}}">
        <input type="hidden" name="_method" value="DELETE">
        {{ csrf_field() }}
        <h2 class="text-center font-alt">Are you sure you want to delete this?</h2>
        <div class="small-8 medium-4 centered columns">
            <input class="button" type="submit" value="Yeah, I'm Sure" />
        </div>
    </form>
</div>
@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.4/js/standalone/selectize.min.js"></script>
    <script>
        $('#tags').selectize({
            delimiter: ',',
            persist: false,
            create: function(input) {
                return {
                    value: input,
                    text: input
                }
            }
        });
    </script>
@endsection