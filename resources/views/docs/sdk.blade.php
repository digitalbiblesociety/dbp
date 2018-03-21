@extends('layouts.app')

@section('head')
    <style>
        h1,h3 {
            text-align: center;
        }

        .icon {
            width:50px;
            height:50px;
            display: block;
            fill:#000;
            float:left;
        }

        .sdk-links a {
            line-height:50px;
            text-indent:10px;
            color:#222;
            font-size:1.2rem;
        }
    </style>
@endsection

@section('content')

    @include('layouts.banner', ['title' => "SDKs"])

    <div class="row">
        <code><pre>swagger-codegen generate -i https://bible.build/swagger2_v4.json -l php -o /Sites/dbp/public/sdk/</pre></code>
    </div>
    <div class="row sdk-links">
        <h3>Downloads <small>2.1.5</small></h3>
        <a href="/sdk/php.zip" class="medium-3 columns"><svg class="icon"><use xlink:href="/img/icons/icons-programming.svg#php"></use></svg> PHP </a>
        <a href="/sdk/php.zip" class="medium-3 columns"><svg class="icon"><use xlink:href="/img/icons/icons-programming.svg#java"></use></svg> Java </a>
        <a href="/sdk/python.zip" class="medium-3 columns"><svg class="icon"><use xlink:href="/img/icons/icons-programming.svg#python"></use></svg> Python </a>
        <a href="/sdk/ruby.zip" class="medium-3 columns"><svg class="icon"><use xlink:href="/img/icons/icons-programming.svg#ruby"></use></svg> Ruby </a>
    </div>

@endsection