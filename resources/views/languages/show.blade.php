@extends('layouts.app')

@section('head')
    <style>

        /* information-tab */

        #information-tab dl {
            margin:90px 10px 0 0;
            padding-right:10px;
            border-right:thin solid #999;
        }

        #information-tab dl dt {
            font-weight: bold;
            float:left;
            margin-right:5px;
        }

        #information-tab dl dd {
            color:#555;
        }

        section[role="banner"] {
            min-height: 200px;
            position: relative;
        }

        .status section[role="banner"] {
            background: #333;
        }

        .status-6 > .legend,
        .status-6 section[role="banner"] {
            background: #ff9966;
            background: -webkit-linear-gradient(to right, #ff5e62, #ff9966);
            background: linear-gradient(to right, #ff5e62, #ff9966);
            color: #FFF
        }

        .status-6 a {
            color: #ff9966 !important
        }

        .status-6 a[aria-selected='true'],
        .status-6 a:hover,
        .status-6 a:focus {
            color: #ff5e62 !important
        }

        .status-5 > .legend,
        .status-5 section[role="banner"] {
            background: #F2994A;
            background: -webkit-linear-gradient(to right, #F2C94C, #F2994A);
            background: linear-gradient(to right, #F2C94C, #F2994A);
            color: #FFF
        }

        .status-5 a {
            color: #F2994A
        }

        .status-4 > .legend,
        .status-4 section[role="banner"] {
            background: #CAC531;
            background: -webkit-linear-gradient(to right, #F3F9A7, #CAC531);
            background: linear-gradient(to right, #F3F9A7, #CAC531);
            color: #FFF
        }

        .status-4 a {
            color: #CAC531
        }

        .status-3 > .legend,
        .status-3 section[role="banner"] {
            background: #00b09b;
            background: -webkit-linear-gradient(to right, #96c93d, #00b09b);
            background: linear-gradient(to right, #96c93d, #00b09b);
            color: #FFF
        }

        .status-3 a {
            color: #00b09b
        }

        .status-2 > .legend,
        .status-2 section[role="banner"] {
            background: #56ab2f;
            background: -webkit-linear-gradient(to right, #a8e063, #56ab2f);
            background: linear-gradient(to right, #a8e063, #56ab2f);
            color: #FFF
        }

        .status-2 a {
            color: #56ab2f
        }

        .status-1 > .legend,
        .status-1 section[role="banner"] {
            background: #52c234;
            background: -webkit-linear-gradient(to right, #061700, #52c234);
            background: linear-gradient(to right, #061700, #52c234);
            color: #FFF
        }

        .status-1 a {
            color: #52c234
        }

        .status-1 a[aria-selected='true'],
        .status-1 a:hover,
        .status-1 a:focus {
            color: #061700 !important
        }

        .status-10 section[role="banner"] {
            background: #222;
            color: #FFF
        }

        h1 {
            padding: 50px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            font-size: 4rem;
        }

        h3 {
            padding: 20px 0;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 900;
            font-size: 2rem;
        }

        .legend {
            text-shadow: 0 1px 1px #000000;
            line-height: 100px;
            font-size: 3rem;
            height: 100px;
            width: 100%;
            text-align: center;
        }

        .language-angle {
            position: absolute;
            height: 200px;
            font-size: 2rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, .2);
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
            background-color: transparent;
            border: none;
        }

        #language-tabs .tabs-title a {
            opacity: .5;
            color: #FFF;
            font-size: 1.25rem;
            -webkit-transition: opacity 1s ease;
            -moz-transition: opacity 1s ease;
            -ms-transition: opacity 1s ease;
            -o-transition: opacity 1s ease;
            transition: opacity 1s ease;
        }

        #language-tabs .tabs-title.is-active a {
            background-color: transparent;
            border-bottom: 7px solid rgba(0, 0, 0, .5);
        }

        #language-tabs .tabs-title a:hover {
            background-color: transparent;
            opacity: 1;
        }

        @media screen and (max-device-width: 500px) {
            #language-tabs .tabs-title.is-active a {
                border: none;
            }
        }

        .edit {
            position: absolute;
            right: 10px;
            top: 10px;
            background-color: rgba(255, 255, 255, .5);
            padding: 5px 20px;
            border-radius: 20px;
        }

        .edit:hover {
            background-color: rgba(255, 255, 255, .5);
            color: #222;
        }

    </style>
@endsection

@section('content')
    <div itemscope itemtype="http://schema.org/Language" class="status status-{{ strtok($language->status," ") }}">

        @include('layouts.partials.banner', [
            'title' => $language->name,
            'breadcrumbs' => [
                '/'           => 'Home',
                '/languages/' => trans('fields.languages'),
                '#'           => $language->name
            ],
            'tabs' => [
                'information-tab'  => 'Information',
                'bibles-tab'       => 'Bibles',
                'resources-tab'    => (!empty($language->resources)) ? 'Resources' : '',
                'countries-tab'    => 'Countries'
            ]
        ])
        @if(\Auth::user()) @if(\Auth::user()->archivist) <a class="button edit" href="{{ route('view_languages.edit', ['id' => $language->id]) }}">Edit</a> @endif @endif

        <div class="row">
            <section role="tabpanel" aria-hidden="false" id="information-tab">
                <div class="medium-3 columns">
                    <dl>
                        <dt>Glottolog Code:</dt><dd>{{ $language->glotto_id }}</dd>
                        <dt>ISO639-3 Code: </dt><dd>{{ $language->iso }}</dd>
                        <dt>Latitude: </dt><dd>{{ $language->latitude }}</dd>
                        <dt>Longitude: </dt><dd>{{ $language->longitude }}</dd>
                        <dt>Location: </dt><dd><a href="/countries/{{ $language->country_id }}">{{ $language->maps }}</a></dd>
                        <dt>Area: </dt><dd>{{ $language->area }}</dd>
                        <dt>Population: </dt><dd>{{ $language->population }}</dd>
                        @isset($language->level) <dt>level: </dt><dd>{{ $language->level }}</dd> @endisset
                        @isset($language->notes) <dt>notes: </dt><dd>{{ $language->notes }}</dd> @endisset
                        @isset($language->typology) <dt>typology: </dt><dd>{{ $language->typology }}</dd> @endisset
                        @isset($language->writing) <dt>writing: </dt><dd>{{ $language->writing }}</dd> @endisset
                        @isset($language->status) <dt>status: </dt><dd>{{ $language->status }}</dd> @endisset
                        @isset($language->scope) <dt>scope: </dt><dd>{{ $language->scope }}</dd> @endisset
                    </dl>
                </div>
                <div class="medium-9 columns">
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
                    <div class="row">
                        <div class="small-4 large-2 columns status-6"><div class="legend">6</div> Threatened </div>
                        <div class="small-4 large-2 columns status-5"><div class="legend">5</div> Developing </div>
                        <div class="small-4 large-2 columns status-4"><div class="legend">4</div> Educational </div>
                        <div class="small-4 large-2 columns status-3"><div class="legend">3</div> Wider communication </div>
                        <div class="small-4 large-2 columns status-2"><div class="legend">2</div> Provincial </div>
                        <div class="small-4 large-2 columns status-1"><div class="legend">1</div> National </div>
                    </div>
                </div>
            </section>

            <section role="tabpanel" aria-hidden="true" id="bibles-tab">
                @if($language->bibles->count() == 0)
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

                @else
                            <table class="table" cellspacing="0" width="100%">
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
                @endif
                </section>

                    <section role="tabpanel" aria-hidden="true" id="resources-tab">
                        <h2>Resources</h2>
                        <table class="table" cellspacing="0" width="100%">
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
                    </section>
                    <section role="tabpanel" aria-hidden="true" id="countries-tab">
                        <h3>Notable Countries</h3>
                        <table class="table" cellspacing="0" width="100%">
                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>Current Name</td>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($language->countries->unique() as $country)
                                <tr>
                                <td>{{ $country->id }}</td>
                                <td><a href="{{ route('view_countries.show', ['country' => $country->id]) }}">{{ $country->name }}</a></td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </section>
        </div>
    </div>
@endsection

@section('footer')

@endsection