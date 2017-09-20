@extends('layouts.app')

@section('head')
<style>
    .banner {
        height:200px;
        background: #222;
        color:#F8f8f8;
    }

    .title {
        text-align: center;
        font-size:2rem;
        padding-top:50px;
    }
    .title small {
        display: block;
        margin-top:15px;
        font-size:.75rem;
    }

    .code-snippet {
        background-color:#f8f8f8;
        box-shadow: rgba(0,0,0,.75) 2px 1px 3px;
        font-size:11px;
        padding:10px;
    }
</style>
@endsection

@section('content')

    <section class="banner">
        <h1 class="title">
            {{ $bible->translations->where('iso',\i18n::getCurrentLocale())->first()->name }}
            <small>{{ $bible->id }}</small>
        </h1>
        <h2>@if(\i18n::getCurrentLocale() != $bible->iso) {{ $bible->translations->where('iso',$bible->iso)->first()->name }} @endif</h2>
    </section>

    <section class="row">
        <div class="medium-6 columns">
            {{ $bible->translations->where('iso',\i18n::getCurrentLocale())->first()->description }}
        </div>
        @if($bible->copyright)
            <div class="medium-6 columns">
                <h3 class="text-center">Include this Bible on your website</h3>
                <p>This Bible's copyright being Public Domain allows us to share it with you! No API key is required! We're working on a library of snippets for different embed methods.</p>
                <b>Gihub Embeds & Plugins</b>
                <ul>
                    <li><a href="">Pure Javascript (In Progress)</a></li>
                    <li><a href="">jQuery (In Progress)</a></li>
                    <li><a href="">Wordpress Plugin (In Progress)</a></li>
                    <li><a href="">Drupal Plugin (In Progress)</a></li>
                </ul>
            </div>
        @endif
    </section>

@endsection