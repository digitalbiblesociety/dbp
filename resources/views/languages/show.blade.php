@extends('layouts.app')

@section('head')
    <style>
        div[role="banner"] {
            min-height:200px;
            position: relative;
        }

        .status div[role="banner"] {
            background: #333;
        }

        .status-6 > .legend,
        .status-6 div[role="banner"] {background:#ff9966;background:-webkit-linear-gradient(to right, #ff5e62, #ff9966);background:linear-gradient(to right, #ff5e62, #ff9966);color:#FFF}
        .status-6 a {color: #ff9966!important}
        .status-6 a[aria-selected='true'],
        .status-6 a:hover,
        .status-6 a:focus {color:#ff5e62!important}

        .status-5 > .legend,
        .status-5 div[role="banner"] {background:#F2994A;background:-webkit-linear-gradient(to right, #F2C94C, #F2994A);background:linear-gradient(to right, #F2C94C, #F2994A);color:#FFF}
        .status-5 a {color: #F2994A}

        .status-4 > .legend,
        .status-4 div[role="banner"] {background:#CAC531;background:-webkit-linear-gradient(to right, #F3F9A7, #CAC531);background:linear-gradient(to right, #F3F9A7, #CAC531);color:#FFF}
        .status-4 a {color: #CAC531}

        .status-3 > .legend,
        .status-3 div[role="banner"] {background:#00b09b;background:-webkit-linear-gradient(to right, #96c93d, #00b09b);background:linear-gradient(to right, #96c93d, #00b09b);color:#FFF}
        .status-3 a {color: #00b09b}

        .status-2 > .legend,
        .status-2 div[role="banner"] {background:#56ab2f;background:-webkit-linear-gradient(to right, #a8e063, #56ab2f);background:linear-gradient(to right, #a8e063, #56ab2f);color:#FFF}
        .status-2 a {color: #56ab2f}

        .status-1 > .legend,
        .status-1 div[role="banner"] {background:#52c234;background:-webkit-linear-gradient(to right, #061700, #52c234);background:linear-gradient(to right, #061700, #52c234);color:#FFF}
        .status-1 a {color: #52c234}
        .status-1 a[aria-selected='true'],
        .status-1 a:hover,
        .status-1 a:focus {color:#061700!important}
        .status-10 div[role="banner"] {background:#222;color:#FFF}
        h1 {
            padding:50px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            font-size: 4rem;
        }

        h3 {
            padding:20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            font-size: 2rem;
        }

        .legend {
            text-shadow: 0 1px 1px #000000;
            line-height:100px;
            font-size:3rem;
            height:100px;
            width:100%;
            text-align: center;
        }
        .language-angle {
            position: absolute;
            height: 200px;
            font-size: 2rem;
            line-height: 1.8;
            color: rgba(255,255,255,.2);
            top: -15px;
            left: 0;
            text-align: justify;
            -moz-transform: rotate(15deg);
            -webkit-transform: rotate(15deg);
            -o-transform: rotate(15deg);
            -ms-transform: rotate(15deg);
            transform: rotate(6deg);
        }

#language-tabs {
    background-color:transparent;
    border:none;
}
#language-tabs .tabs-title a {
    opacity: .5;
    color:#FFF;
    font-size:1.25rem;
    -webkit-transition: opacity 1s ease;
    -moz-transition: opacity 1s ease;
    -ms-transition: opacity 1s ease;
    -o-transition: opacity 1s ease;
    transition: opacity 1s ease;
}

        #language-tabs .tabs-title.is-active a {
            background-color:transparent;
            border-bottom: 7px solid rgba(0,0,0,.5);
        }

        #language-tabs .tabs-title a:hover {
            background-color:transparent;
            opacity: 1;
        }

        @media screen and (max-device-width: 500px) {
            #language-tabs .tabs-title.is-active a {
                border: none;
            }
        }

        .edit {
            position: absolute;
            right:10px;
            top:10px;
            background-color: rgba(255,255,255,.5);
            padding:5px 20px;
            border-radius: 20px;
        }

        .edit:hover {
            background-color: rgba(255,255,255,.5);
            color:#222;
        }

    </style>
@endsection

@section('content')
<div itemscope itemtype="http://schema.org/Language"  class="status status-{{ strtok($language->status," ") }}">
<div role="banner">
    @if(\Auth::user()) @if(\Auth::user()->archivist) <a class="button edit" href="{{ route('view_languages.edit', ['id' => $language->id]) }}">Edit</a> @endif @endif
    <h1 itemprop="name" class="text-center">{{ $language->name }}</h1>
    <h2 itemprop="alternateName" class="text-center">{{ $language->autonym }}</h2>
    <small class="language-angle">
        @foreach($language->translations as $translation)
            <span itemprop="alternateName">{{ $translation->name }}</span>
        @endforeach
    </small>
    <div class="tabs medium-10 columns centered text-center" data-tabs id="language-tabs">
        <div><a href="#information" aria-selected="true">Information</a></div>
        <div><a data-tabs-target="bibles" href="#bibles">Bibles</a></div>
        @if(!empty($language->resources))
        <div><a data-tabs-target="resources" href="#resources">Resources</a></div>
        @endif
        <div><a data-tabs-target="countries" href="#countries">Countries</a></div>
    </div>
</div>

    <div class="tabs-content row" data-tabs-content="language-tabs">
        <div class="tabs-panel is-active" id="information">
                <div class="medium-3 columns">
                        <p><b>Glottolog Code:</b> <small itemprop="value">{{ $language->glotto_id }}</small></p>
                        <p><b>ISO639-3 Code: </b> <small itemprop="value">{{ $language->iso }}</small></p>
                        <p><b>Latitude: </b><small>{{ $language->latitude }}</small></p>
                        <p><b>Longitude: </b><small>{{ $language->longitude }}</small></p>
                        <p><b>Location: </b> <a href="/countries/{{ $language->country_id }}">{{ $language->maps }}</a></p>
                        <p><b>Area: </b><small>{{ $language->area }}</small></p>
                        <p><b>Population: </b><small>{{ $language->population }}</small></p>
                        @isset($language->level) <p><b>level:</b> <small>{{ $language->level }}</small></p> @endisset
                        @isset($language->notes) <p><b>notes:</b> <small>{{ $language->notes }}</small></p> @endisset
                        @isset($language->typology) <p><b>typology:</b> <small>{{ $language->typology }}</small></p> @endisset
                        @isset($language->writing) <p><b>writing:</b> <small>{{ $language->writing }}</small></p> @endisset
                        @isset($language->status) <p><b data-open="scopeReveal">status (?):</b> <small>{{ $language->status }}</small></p> @endisset
                        @isset($language->scope) <p><b>scope:</b><small>{{ $language->scope }}</small></p> @endisset
                </div>
                <div class="medium-9 columns">
                    @if($language->bibles->count() == 0)
                    <div class="callout secondary">
                        <section class="apology">
                            <h2>No Bibles have been discovered online</h2>

                            @if($language->bible_year)
                                <p>However we do know a Complete Bible has been completed in this language around {{ $language->bible_year }}</p>
                            @elseif($language->bible_year_newTestament)
                                <p>However we do know a New Testament has been completed in this language around {{ $language->bible_year_newTestament }}</p>
                            @elseif($language->bible_year_portions)
                                <p>However we do know portions were translated in this language around {{ $language->bible_year_portions }}</p>
                            @else
                                <p>Worse still, we don't think a Bible has even been translated for this language.</p>
                            @endif

                            <p>Regardless, just because we haven't been able to archive a text in this language doesn't mean that there isn't one. There are plenty of places online where texts are kept. Maybe you've run across it in your online travels? Anyways if you'd like to help us find it that'd be great!</p>
                            <p>We've find these resource sites to be rather rich hunting grounds in our searches for obscure biblical texts. I probably need to apologize in advance for sending you back to google if you just arrived here from google but let's face it google is a great resource for finding great resources</p>
                            <div class="button-group small-12 large-6 columns centered">
                                <a href="https://archive.org/search.php?query={{$language->name}}&and[]=mediatype%3A%22texts%22" class="button">Archive.org</a>
                                <a href="https://www.gutenberg.org/ebooks/search/?query={{$language->name}}&go=Go" class="button">Project Gutenberg</a>
                                <a href="http://google.com" class="button">Google</a>
                            </div>
                        </section>
                        @endif
                    </div>
                    @isset($language->currentTranslation)
                        <h3>Overview</h3>
                        <p itemprop="description">{{ $language->currentTranslation->description }}</p>
                    @endisset
                    @isset($language->development)
                        <h3>Development:</h3>
                        <p>{{ $language->development }}</p>
                    @endisset
                    @isset($language->use)
                        <h3>use:</h3>
                        <p>{{ $language->use }}</p>
                    @endisset
                    @isset($language->population_notes)
                        <h3>Population Notes:</h3>
                        <p>{{ $language->population_notes }}</p>
                    @endisset



                        <!-- This is the first modal -->
                        <div class="reveal" id="scopeReveal" data-reveal>
                            <div class="row">
                                <div class="medium-4 columns status-6"><div class="legend">6</div>Threatened</div>
                                <div class="medium-7 columns"></div>
                            </div>
                            <div class="row">
                                <div class="medium-4 columns status-5"><div class="legend">5</div>Developing</div>
                                <div class="medium-7 columns"></div>
                            </div>
                            <div class="row">
                                <div class="medium-4 columns status-4"><div class="legend">4</div>Educational</div>
                                <div class="medium-7 columns"></div>
                            </div>
                            <div class="row">
                                <div class="medium-4 columns status-3"><div class="legend">3</div>Wider communication</div>
                                <div class="medium-7 columns"></div>
                            </div>
                            <div class="row">
                                <div class="medium-4 columns status-2"><div class="legend">2</div>Provincial</div>
                                <div class="medium-7 columns"></div>
                            </div>
                            <div class="row">
                                <div class="medium-4 columns status-1"><div class="legend">1</div>National</div>
                                <div class="medium-7 columns"></div>
                            </div>
                            <button class="close-button" data-close aria-label="Close reveal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>


                </div>
        </div>
        <div class="tabs-panel" id="bibles">
            <table>
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Current Name</td>
                    <td>Vernacular Name</td>
                    <td>Date</td>
                </tr>
                </thead>
                <tbody>
            @foreach($language->bibles as $bible)
                <tr>
                    <td><a href="{{ route('view_bibles.show', ['bible' => $bible->id]) }}">{{ $bible->id }}</a></td>
                    <td>{{ $bible->currentTranslation->name }}</td>
                    <td></td>
                    <td>{{ $bible->date }}</td>
                </tr>
            @endforeach
                </tbody>
            </table>
        </div>
        <div class="tabs-panel" id="resources">
            <table>
                <thead>
                <tr>
                    <td>ID</td>
                    <td>Current Name</td>
                    <td>Vernacular Name</td>
                    <td>Date</td>
                </tr>
                </thead>
                <tbody>
                {{-- @foreach($language->resources as $resource) --}}
                {{--     {{ $resource }} --}}
                {{-- @endforeach --}}
                </tbody>
            </table>
        </div>
        <div class="tabs-panel" id="countries">
            <h3>Notable Countries</h3>
            <table>
                <thead>
                    <tr>
                        <td>ID</td>
                        <td>Current Name</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($language->countries->unique() as $country)
                    <td>{{ $country->id }}</td>
                    <td><a href="{{ route('view_countries.show', ['country' => $country->id]) }}">{{ $country->name }}</a></td>
                @endforeach
                </tbody>
            </table>
        </div>


    </div>
</div>
@endsection

@section('footer')

@endsection