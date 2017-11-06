@extends('layouts.app')

@section('head')
    <title>{{ $alphabet->name }} | {{ trans('fields.siteTitle') }}</title>
    @if(isset($alphabet->description_short))
        <meta name="description" content="{{ $alphabet->description_short }}">
    @endif

    <style>
        p {padding:0 10px}
        #alphabet-show .fonts {
            padding-bottom:25px;
        }
        #alphabet-show .fonts a {
            position: relative;
            display: block;
            float:right;
            height: 25px;
            width:25px;
        }

        .overlay {
            position: absolute;
            top: 91px;
            right: 0;
            z-index: 99;
            color: rgba(255,255,255,.75);
            height: 233px;
            overflow: hidden;
        }

        .overlay span {
            transform: rotate(-25deg);
            font-size: 2rem;
            display: block;
            position: relative;
            right: -60%;
            top: -50px;
        }


        @media only screen and (max-width:40.0625em) {
            .overlay {
                height:196px;
                color: rgba(255,255,255,.25);
            }
            .overlay span {
                right: auto;
                top: auto;
            }
        }

        .title {
            text-align: center;
        }
        .title .subtitle {
            display: block;
        }

        .features {
            display: block;
            background: #333;
            width:100%;
            min-height:40px;
            margin:15px 5px;
        }
        .features li {
            background: #333;
            display: block;
            float:left;
            height:40px;
            line-height:40px;
            width:20%;
            text-align: center;
            color:#FFF;
        }


        .features li.disabled {text-decoration: line-through}

    </style>
@endsection

@section('content')
    <div id="alphabet-show">

        <div class="row">
            <h1 class='title'>{{ $alphabet->name }} <small class='subtitle'>{{ $alphabet->subtitle }}</small></h1>
            <small class="code"></small>
            <div class="features">
                <li class="{{ ($alphabet->diacritics) ? "enabled" : "disabled" }}">Diacritics</li>
                <li class="{{ ($alphabet->contextual_forms) ? "enabled" : "disabled" }}">Contextual Forms</li>
                <li class="{{ ($alphabet->reordering) ? "enabled" : "disabled" }}">Reordering</li>
                <li class="{{ ($alphabet->split_graphs) ? "enabled" : "disabled" }}">Graphs</li>
                <li class="{{ ($alphabet->ligatures) ? "enabled" : "disabled" }}">Ligatures</li>
            </div>
        </div>

        {{ $alphabet->unicode_pdf }}
        {{ $alphabet->white_space }}
        {{ $alphabet->complex_positioning }}
        {{ $alphabet->open_type_tag }}
        {{ $alphabet->unicode }}
        {{ $alphabet->case }}
        {{ $alphabet->status }}
        {{ $alphabet->baseline }}
        {{ $alphabet->sample }}
        {{ $alphabet->sample_img }}

        <div class="overlay">
            <span>{{ $alphabet->sample }}</span>
        </div>

        <div class="row">
            <div class="small-4 columns">
                <h3>Meta Data</h3>
                <ul>
                    <li><b>Script ID:</b> {{$alphabet->script}}</li>
                    <li><b>Type:</b> {{$alphabet->type}}</li>
                    <li><b>Direction:</b> {{$alphabet->direction}} <span class="direction-notes">{{ $alphabet->direction_notes }}</span></li>
                    <li><b>Family:</b> {{ $alphabet->family }}</li>
                </ul>
                <h3>Languages</h3>
                <ul>
                    @foreach($alphabet->languages as $language)
                        <li><a href="/languages/{{ $language->id }}">{{ $language->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="small-8 columns">
                <h3>Fonts</h3>
                @foreach($alphabet->fonts as $font)
                    <div class="callout secondary fonts">

                        <div class="title">{{ $font->fontName }}</div>
                        @if(file_exists(public_path()."/fonts/".$font->fontFileName.".ttf"))
                            <a href="/fonts/{{ $font->fontFileName }}.ttf">Download</a>
                        @elseif(file_exists(public_path()."/fonts/".$font->fontFileName.".otf"))
                            <a href="/fonts/{{ $font->fontFileName }}.otf">Download</a>
                        @endif

                        <p>{{ $font->fontWeight }}</p>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="medium-8 columns"><h3>Description</h3><p class="text-justify">{!! $alphabet->description !!}</p></div>
            <div class="medium-4 columns">
                <div class="row">
                    <h3>Bibles</h3>
                    <table>
                        <thead>
                        <tr>
                            <td>Bible ID</td>
                            <td>Bible Name</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($alphabet->bibles as $bible)
                            <tr>
                                <td>{{ $bible->id }}</td>
                                <td>{{ $bible->currentTranslation->name ?? '' }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection