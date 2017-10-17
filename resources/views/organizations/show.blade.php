@extends('layouts.app')

@section('head')
    <style>
        h1 {text-align: center}

        section[role="banner"] {
            min-height:200px;
            @isset($organization->primaryColor)
                background: linear-gradient(148deg, {{ $organization->primaryColor }} 0%, {{ $organization->secondaryColor }} 100%);
            @endisset
        }

        .organization-connection {

        }
    </style>
@endsection

@section('content')

    <section role="banner">
        @isset($organization->currentTranslation)
            <h1>{{ $organization->currentTranslation->name }}</h1>
        @endisset
        @isset($organization->vernacularTranslation)
            <h1>{{ $organization->vernacularTranslation->name }}</h1>
        @endisset
    </section>

    <section role="tabpanel" aria-hidden="false" id="information" class="row">
        <div class="row">
            {{-- @if(file_exists('/img/partners/'.strtolower(str_replace(" ", "-",$organization->en_name)).'_logo.jpg')) --}}
            <div class="medium-4 columns publisher-logo">
                <img src="{{ $organization->logo }}" />
                <nav class="social-links text-center">
                    @isset($organization->url_website)  <a itemprop="url" title="website" href="{{ $organization->url_website }}">Website</a>  @endisset
                    @isset($organization->url_twitter)  <a itemprop="sameAs" class="twitter" title="Twitter" href="{{ $organization->url_twitter }}">Twitter</a> @endisset
                    @isset($organization->url_facebook) <a itemprop="sameAs" class="facebook" title="Facebook" href="{{ $organization->url_facebook }}">Facebook</a> @endisset

                    @isset($organization->fobai)    <span>Fobai</span> @endisset
                    @isset($organization->dbl)
                        <div class="organization-connection media-object">
                            <div class="media-object-section">
                                <div class="thumbnail"><img src="http://images.bible.cloud/partners_dbl_link.svg" /></div>
                            </div>
                            <div class="media-object-section">
                                <h4>Digital Bible Library <small>{{ $organization->dbl->relationship_id }}</small></h4>
                            </div>
                        </div>
                    @endisset
                </nav>
            </div>
            {{-- @endif --}}
            <div class="medium-8 columns">
                <p class="publisher-text" itemprop="description">{!! (isset($organization->currentTranslation) ? $organization->currentTranslation->description : "") !!}</p>
            </div>
        </div>
        <small class="publisher-contact text-center">
            <div itemprop="telephone"> {{ $organization->phone or '' }} </div>
            <div><a href="mailto:{{ $organization->email }}" itemprop="email"> {{ $organization->email }} </a></div>
            <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress"> {{ $organization->address or '' }} </div>
        </small>
    </section>

    <section role="tabpanel" aria-hidden="true" id="resources" class="row">

        <table class="table bible_table no-fouc" cellspacing="0" width="100%" data-route="organizations/{{ $organization->slug }}/bibles" data-invisiblecolumns="0,1,2,3">
            <thead>
            <tr>
                <th>{{ trans('fields.continent') }}</th>
                <th>{{ trans('fields.image') }}</th>
                <th>{{ trans('fields.size') }}</th>
                <th>{{ trans('fields.options') }}</th>
                <th>{{ trans('fields.country') }}</th>
                <th>{{ trans('fields.language') }}</th>
                <th>{{ trans('fields.vernacularTitle') }}</th>
                <th>{{ trans('fields.title') }}</th>
                <th>{{ trans('fields.date') }}</th>
                <th>{{ trans('fields.abbr') }}</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </section>


@endsection