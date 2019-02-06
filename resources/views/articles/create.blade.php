@extends('layouts.main')

@section('head')
    <title>{{ trans('main.title_articles_page') }} | {{ trans('fields.siteTitle') }}</title>
    <meta type="description" content="{{ trans('main.title_articles_description') }}" />
@endsection

@section('body')

@include('layouts.banner', [
    'title'           => "Create Article",
    'subtitle'        => "Share Your Thoughts",
    'blurryImage'     => 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQEASABIAAD/2wBDAH5lZZ5ynvaRkfb/8MTw///w8PDw////////////////////////////////////////////////////////////2wBDAYSensqxyvCXl/D/8Mrw////////////Qv//////////////////////////////////////////////////////wAARCAAKADADASIAAhEBAxEB/8QAFwABAQEBAAAAAAAAAAAAAAAAAAIDAf/EABoQAQEAAwEBAAAAAAAAAAAAAAABAhExAyL/xAAUAQEAAAAAAAAAAAAAAAAAAAAA/8QAFBEBAAAAAAAAAAAAAAAAAAAAAP/aAAwDAQACEQMRAD8Av0y0z3a1ySCZd9M/kpAc1Ws4AP/Z',
    'backgroundImage' => "https://images.bible.cloud/web/banners/roberts_bibles1.jpg",
    'noGradient'      => true,
    'breadcrumbs' => [
        route('dbs_home')           => trans('fields.home'),
        route('dbs_about')          => trans('fields.about'),
        route('dbs_articles.index') => trans('fields.articles'),
        '#'                         => trans('fields.create'),
    ]
])

<form action="/articles" method="POST">
    @include('articles.form')
</form>

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