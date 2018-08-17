@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title' => trans('about.license_title'),
        'breadcrumbs' => [
            '/'     => trans('about.home'),
            '/docs' => trans('about.documentation'),
            '#'     => trans('about.license')
        ]
    ])

    <div class="column is-8-desktop is-offset-2-desktop">
    <div class="box columns">
        <div class="column is-6 has-text-centered">
            <svg class="icon is-size-3"><use xlink:href="/images/icons.svg#scales"></use></svg>
            <p class="is-size-6 has-text-justified">{{ trans('about.license_description') }}</p>
        </div>

        <div class="column is-6">
            <div class="columns">
            <div class="column is-4-desktop">
                <h5 class="has-text-centered has-text-weight-bold mb10">{{ trans('about.license_allow_title') }}</h5>
                <ul>
                    <li>
                        <svg class="icon has-text-success"><use xlink:href="/images/icons.svg#checkmark"></use></svg>
                        <span aria-label="{{ trans('about.license_allow_commercial_description') }}">{{ trans('about.license_allow_commercial') }}</span>
                    </li>
                    <li>
                        <svg class="icon has-text-success"><use xlink:href="/images/icons.svg#checkmark"></use></svg>
                        <span aria-label="{{ trans('about.license_allow_modification_description') }}">{{ trans('about.license_allow_modification') }}</span>
                    </li>
                    <li>
                        <svg class="icon has-text-success"><use xlink:href="/images/icons.svg#checkmark"></use></svg>
                        <span aria-label="{{ trans('about.license_allow_distribution_description') }}">{{ trans('about.license_allow_distribution') }}</span>
                    </li>
                    <li>
                        <svg class="icon has-text-success"><use xlink:href="/images/icons.svg#checkmark"></use></svg>
                        <span aria-label="{{ trans('about.license_allow_private_description') }}">{{ trans('about.license_allow_private') }}</span>
                    </li>
                </ul>
            </div>
            <div class="column is-4-desktop">
                <h5 class="has-text-centered has-text-weight-bold mb10">{{ trans('about.license_deny_title') }}</h5>
                <ul>
                    <li>
                        <svg class="icon has-text-danger"><use xlink:href="/images/icons.svg#xmark"></use></svg>
                        <span aria-label="{{ trans('about.license_deny_liability_description') }}">{{ trans('about.license_deny_liability') }}</span>
                    </li>
                    <li>
                        <svg class="icon has-text-danger"><use xlink:href="/images/icons.svg#xmark"></use></svg>
                        <span aria-label="{{ trans('about.license_deny_warranty_description') }}">{{ trans('about.license_deny_warranty') }}</span>
                    </li>
                </ul>
            </div>
                <div class="column is-4-desktop">
                    <h5 class="has-text-centered has-text-weight-bold mb10">{{ trans('about.license_condition_title') }}</h5>
                    <ul>
                        <li>
                            <span aria-label="{{ trans('about.license_condition_include_description') }}">{{ trans('about.license_condition_include') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

        <nav class="level is-mobile">
            <div class="level-left is-size-7">
                <a class="level-item has-text-grey" href="https://github.com/digitalbiblesociety/dbp/blob/master/LICENSE.md">{{ trans('about.license_full_license') }}</a>
                <a class="level-item has-text-grey" href="https://en.wikipedia.org/wiki/MIT_License">{{ trans('about.license_read_more') }}</a>
            </div>
        </nav>
    </div>

@endsection


