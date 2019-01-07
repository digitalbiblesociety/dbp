@extends('layouts.app')

@section('head')
    <title>{{ trans('app.bibles_title') }}</title>
    <meta type="description" property="og:description" content="{{ trans('app.bibles_description') }}" />
    @include('layouts.partials.ogp')
@endsection

@section('content')

    @include('layouts.partials.banner', [
        'title' => trans('app.bibles_title'),
        'breadcrumbs' => [
            '/'     => 'Home',
            '/wiki' => trans('app.wiki_title'),
            '#'     => trans('app.bibles_title')
        ]
    ])

    @if(isset($bibles))
        <div class="container">
        <table class="table" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
                @foreach($bibles as $bible)
                    <tr>
                        <td>{{ $bible->id }}</td>
                        <td>{{ $bible->translations->where('iso','eng')->first()->name }}</td>
                        <td>{{ $bible->date }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

    @else

    <div class="container">
        <algolia-bible-search></algolia-bible-search>
    </div>

    @endif
@endsection