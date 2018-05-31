@extends('layouts.app')

@section('head')
    <title>{{ trans('wiki_numerals.show_title', ['alphabet_name' => $alphabet->name]) }}</title>
    <style>
        .banner     {text-align: center;background: #010101;color:#FFF;padding:40px}
        .banner h1  {font-size:1.5rem}
        .banner h3  {font-size:1rem}
    </style>
@endsection

@section('content')

    <section class="banner">
        <h1>{{ trans('wiki_numerals.show_title', ['alphabet_name' => $alphabet->name]) }}</h1>
        <div class="row">
            <div class="medium-6 columns">
                <h3>{{ trans('wiki_numerals.show_languageSupport') }}</h3>
                    @foreach($alphabet->languages as $language)
                        <div><a href="{{ route('view_languages.show', ['id' => $language->id], false) }}">{{ $language->name }} [{{ $language->iso }}]</a></div>
                    @endforeach
            </div>
            <div class="medium-6 columns">
                <h3>{{ trans('wiki_numerals.show_bibleSupport') }}</h3>
                @foreach($alphabet->languages as $language)
                    @foreach($language->bibles as $bible)
                        <div><a href="{{ $bible->id }}">{{ $bible->translations->where('iso','eng')->first()->name }}</a></div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    <table class="row">
        <thead>
            <tr>
                <td>{{ trans('wiki_numerals.fields_numeral') }}</td>
                <td>{{ trans('wiki_numerals.fields_numeral_vernacular') }}</td>
                <td>{{ trans('wiki_numerals.fields_numeral_written') }}</td>
            </tr>
        </thead>
        <tbody>
        @foreach($numerals as $numeral)
            <tr>
                <td>{{ $numeral->numeral }}</td>
                <td>{{ $numeral->numeral_vernacular }}</td>
                <td>{{ $numeral->numeral_written }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection