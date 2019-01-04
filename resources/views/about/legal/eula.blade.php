@extends('layouts.app')

@section('head')
    <style>
        p {
            text-align: justify;
            margin-top:10px;
        }
    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', [
    'title' => trans('about.eula_title'),
    'breadcrumbs' => [
        '/'     => trans('about.home'),
        '/docs' => trans('about.documentation'),
        '#'     => trans('about.eula_title')
    ]
])

    <div class="container">
        <div class="tabs is-centered">
            <ul>
                <li><a href="{{ route('legal') }}">{{ trans('about.legal_overview') }}</a></li>
                <li class="is-active"><a href="{{ route('eula') }}">{{ trans('about.eula_title') }}</a></li>
                <li><a href="{{ route('license') }}">{{ trans('about.license_title') }}</a></li>
                <li><a href="{{ route('privacy_policy') }}">{{ trans('about.privacy_policy_title') }}</a></li>
            </ul>
        </div>
        <section class="box">
            <h2 class="is-size-4">{{ trans('about.eula_license_title') }}</h2>
            <p>{{ trans('about.eula_introduction_use') }}</p>
            <p>{{ trans('about.eula_introduction_legal') }}</p>
            <p>{{ trans('about.eula_license_description') }}</p>
            <h2 class="is-size-4">{{ trans('about.eula_third_party_title') }}</h2>
            <p>{{ trans('about.eula_third_party_includes') }}</p>
            <p>{{ trans('about.eula_third_party_acknowledge') }}</p>
            <p>{{ trans('about.eula_third_party_dismissal') }}</p>
            <h2 class="is-size-4">{{ trans('about.eula_terms') }}</h2>
            <p>{{ trans('about.eula_terms_termination') }}</p>
            <p>{{ trans('about.eula_terms_comply') }}</p>
            <p>{{ trans('about.eula_terms_cease') }}</p>
            <h2 class="is-size-4">{{ trans('about.eula_amendments_title') }}</h2>
            <p>{{ trans('about.eula_amendments_description') }}</p>
            <h2 class="is-size-4">{{ trans('about.eula_agreement_title') }}</h2>
            <p>{{ trans('about.eula_agreement_entire') }}</p>
            <p>{{ trans('about.eula_agreement_additional') }}</p>
        </section>
        <small class=" is-size-7 has-text-centered has-text-grey">Last updated: August 22, 2018</small>
    </div>
@endsection