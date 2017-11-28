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
        <h2>Requests</h2>
        <div class="medium-4 columns">

        </div>
    </section>


@endsection