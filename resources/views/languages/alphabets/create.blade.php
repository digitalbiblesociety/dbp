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

    <section class="row">
        <h3>Create Alphabet</h3>
        <a href="">Field Descriptions, restrictions and requirements</a>
    </section>

    <form action="/alphabets" method="POST">
        {{ csrf_field() }}
        @include('languages.alphabets.form')
    </form>


@endsection