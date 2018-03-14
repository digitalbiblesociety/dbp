@extends('layouts.app')

@section('head')

    <style>
        dd {
            padding-bottom:110px;
        }

        .tag {
            padding:5px;
            font-size:12px;
            font-weight: normal;
            color:#FFF;
            background-color:green;
            margin:0 5px;
            float:left;
            border-radius: 10px;
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
            <li class="tabs-title"><a data-tabs-target="field_descriptions" href="#field_descriptions">Database Field Description</a></li>
        </ul>
    </section>

    <form action="{{ route('alphabets.store') }}" method="POST">
        {{ csrf_field() }}
        @include('languages.alphabets.form')
    </form>

    <section id="database-field-descriptions" class="row">
        @include('languages.alphabets.field_descriptions')
    </section>

@endsection