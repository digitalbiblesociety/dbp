@extends('layouts.app')

@section('head')

    <style>
        .tag {
            padding:5px;
            font-size:12px;
            font-weight: normal;
            color:#FFF;
        }

        .tag.unique {
            background: #48B;
        }
        
        .tag.required {
            background-color: #844;
        }

        .field-definitions .card {
            min-height:250px;
        }

    </style>

@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => 'Create Alphabet'])

    <section class="row">
        <ul class="tabs" data-tabs id="example-tabs">
            <li class="tabs-title is-active"><a href="#fields" aria-selected="true">Single Creation</a></li>
            <li class="tabs-title"><a data-tabs-target="field_descriptions" href="#field_descriptions">Field Descriptions</a></li>
        </ul>
    </section>

    <form action="{{ route('alphabets.store') }}" method="POST">
        {{ csrf_field() }}
        @include('languages.alphabets.form')
    </form>


@endsection