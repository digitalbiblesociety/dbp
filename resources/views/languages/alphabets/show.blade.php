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
    </style>
@endsection

@section('content')
    <div id="alphabet-show">


        <span class='title'> $alphabet->name,</span>
        <span class='subtitle'> $alphabet->subtitle,</span>
        <span class='backgroundImage'>$alphabet->family</span>
    <span class='backgroundBase64'> $alphabet->backgroundBase64</span>



        <div class="overlay">
            <span>{{ $alphabet->sample }}</span>
        </div>

        <div class="row">
            <div class="small-4 columns">
                <h3>Description</h3>
                <p class="text-justify">{{ $alphabet->description }}</p>

                <h3>Meta Data</h3>
                <ul>
                    <li><b>Script:</b> {{$alphabet->script}}</li>
                    <li><b>Type:</b> {{$alphabet->type}}</li>
                    <li><b>Direction:</b> {{$alphabet->direction}}</li>
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
    </div>
@endsection