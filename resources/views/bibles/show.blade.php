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

    .fileset {
        padding:10px;
        box-shadow: rgba(0,0,0,.75) 2px 1px 3px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        transition: all 0.3s cubic-bezier(.25,.8,.25,1);
    }
    .fileset:hover {
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }

    .fileset img {
        width:50px;
        display: block;
    }
</style>
@endsection

@section('content')

    <section class="banner">
        <h1 class="title">
            {{ $bible->translations->where('iso',\i18n::getCurrentLocale())->first()->name }}
            <small>{{ $bible->id }}</small>
        </h1>

        <h2>
            {{-- If the current Vernacular Title does not match the current title --}}
            @if($bible->translations->where('iso',\i18n::getCurrentLocale())->first()->name != $bible->translations->where('iso',$bible->iso)->first()->name)
                {{ $bible->translations->where('iso',$bible->iso)->first()->name }}
            @endif
        </h2>
    </section>

    <section class="row">
        <div class="medium-7 columns">
            <h4>Description</h4>
            {{ $bible->translations->where('iso',\i18n::getCurrentLocale())->first()->description }}
            <h4>Reference Filesets</h4>
            <div class="expanded button-group">
                @foreach($bible->filesets as $fileset)
                    <a class="button" href="/bibles/{{ $fileset->id }}/epub/{{ $fileset->id }}.epub">ePub</a>
                    <a class="button" href="/bibles/{{ $fileset->id }}/mobi/{{ $fileset->id }}.mobi">mobi</a>
                    <a class="button" href="/bibles/{{ $fileset->id }}/inscript/index.html">inScript</a>
                    <a class="button" href="/bibles/{{ $fileset->id }}/html/index.html">HTML</a>
                @endforeach
            </div>
        </div>

            <div class="medium-5 columns">
                <div class="row">
                <h3 class="text-center">Include this Bible on your website</h3>
                @if(($bible->copyright == "Public Domain") OR (substr($bible->copyright,0,2) == "CC"))
                    <p>This Bible's copyright allows us to share it with you! No API key is required! We're working on a library of snippets for different embed methods.</p>
                @else
                    <p>You'll need to be given access to this Bible if you want to use it.</p>
                    <a class="button expanded" href="bibles/{{ $bible->id }}/request-access">Request Access</a>
                @endif
                </div>
                <b>Gihub Embeds & Plugins</b>
                <ul>
                    <li><a href="">Pure Javascript (In Progress)</a></li>
                    <li><a href="">jQuery (In Progress)</a></li>
                    <li><a href="">Wordpress Plugin (In Progress)</a></li>
                    <li><a href="">Drupal Plugin (In Progress)</a></li>
                </ul>
            </div>

    </section>

@endsection