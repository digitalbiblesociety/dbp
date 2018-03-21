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
        position: relative;
    }
    .fileset:hover {
        box-shadow: 0 14px 28px rgba(0,0,0,0.25), 0 10px 10px rgba(0,0,0,0.22);
    }

    .fileset img {
        width:50px;
        display: block;
    }

    .fileset span {
        display: block;
        position: absolute;
        top:3px;
        right:3px;
    }

</style>
@endsection

@section('content')

    <section class="banner">
        <h1 class="title">
            {{ $bible->currentTranslation->name }}
            <div class="subtitle">
            {{-- If the current Vernacular Title does not match the current title --}}
                @if($bible->currentTranslation->name != $bible->vernacularTranslation->name)
                    {{ $bible->vernacularTranslation->name }}
                @endif
            </div>
            <small>{{ $bible->id }}</small>
        </h1>

    </section>

    <section class="row">
        <div class="medium-7 columns">
            <h4>Description</h4>
            {{ $bible->currentTranslation->description }}
            <h4>Filesets</h4>
            @foreach($bible->filesets as $fileset)
                @if(!$fileset->hidden)
                    <div class="expanded button-group">
                        <a class="button fileset" href="/bibles/filesets/{{ $fileset->id }}/permissions">
                            {{ trans('fields.set_type_code_'.$fileset->set_type_code) }}
                            {{ trans('fields.set_size_code_'.$fileset->set_size_code) }}
                            <small>{{ $fileset->id }}</small>
                        </a>
                    </div>
                @endif
            @endforeach

            <h4>Links</h4>
            @foreach($bible->links as $link)
                <div class="panel"><a href="{{ $link->url }}"><b>{{ $link->type }}</b> | <span>{{ $link->title }}</span></a></div>
            @endforeach

            <h4>Organizations</h4>
            @foreach($bible->organizations as $organization)
                <div class="panel"><a href="{{ $organization->id }}">{{ $organization->pivot->relationship_type }} | {{ $organization->currentTranslation->name }}</a></div>
            @endforeach

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