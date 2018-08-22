@extends('layouts.app')

@section('content')

@include('layouts.partials.banner', [
    'title' => trans('about.privacy_policy_title'),
    'breadcrumbs' => [
        '/'     => trans('about.home'),
        '/docs' => trans('about.documentation'),
        '#'     => trans('about.privacy_policy_title')
    ]
])

<div class="container">

    <div class="tabs is-centered">
        <ul>
            <li><a href="{{ route('legal') }}">{{ trans('about.legal_overview') }}</a></li>
            <li><a href="{{ route('eula') }}">{{ trans('about.eula_title') }}</a></li>
            <li><a href="{{ route('license') }}">{{ trans('about.license_title') }}</a></li>
            <li class="is-active"><a href="{{ route('privacy_policy') }}">{{ trans('about.privacy_policy_title') }}</a></li>
        </ul>
    </div>

    <div class="box columns">

    </div>

    <nav class="level is-mobile">
        <div class="level-left is-size-7">
            <a class="level-item has-text-grey" href="https://github.com/digitalbiblesociety/dbp/blob/master/LICENSE.md">{{ trans('about.license_full_license') }}</a>
            <a class="level-item has-text-grey" href="https://en.wikipedia.org/wiki/MIT_License">{{ trans('about.license_read_more') }}</a>
        </div>
    </nav>
</div>


@endsection