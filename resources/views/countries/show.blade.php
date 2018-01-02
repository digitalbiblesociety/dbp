@extends('layouts.app')

@section('head')
    <title>{{ $country->name }}</title>
    <style>
        div[role="banner"] {
            background-color:#061700;
            color:#FFF;
        }
        h1 {
            padding:50px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            font-size: 4rem;
            text-align: center;
        }

        h3 {
            padding:20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            font-size: 2rem;
        }

        tr[data-links="0"][data-filesets="0"] {
            opacity: .3;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => $country->name])

    <div class="row">
    <table class="table" cellspacing="0" width="100%">
        <thead>
        <tr>
            <td>Iso</td>
            <td>Language</td>
            <td>Name</td>
            <td>Date</td>
            <td>Scope</td>
            <td>Script</td>
            <td>Count</td>
            <td>Links</td>
        </tr>
        </thead>
    <tbody>
        @foreach($country->languages as $language)
            @foreach($language->bibles as $bible)
                <tr data-filesets="{{ $bible->filesets->count() }}" data-links="{{ $bible->links->count() }}">
                    <td>{{ $bible->language->iso }}</td>
                    <td>{{ $bible->language->name }}</td>
                    <td><a href="{{ route('view_bibles.show', $bible->id) }}">{{ $bible->currentTranslation->name }}</a></td>
                    <td>{{ $bible->date }}</td>
                    <td>{{ $bible->scope }}</td>
                    <td>{{ $bible->script }}</td>
                    <td>{{ $bible->filesets->count() }}</td>
                    <td>{{ $bible->links->count() }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
    </table>
    </div>

    <div>{{ $country->id }}</div>
    <div>{{ $country->iso_a3 }}</div>
    <div>{{ $country->fips }}</div>
    <div>{{ $country->continent }}</div>


@endsection