@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title'     => trans('app.site_name'),
        'subtitle'  => trans('app.site_description'),
        'size'      => 'medium',
        'image'     => '/images/dbp_icon.svg'
    ])

<section class="container">
    <div class="columns features level">

        <div class="column is-4 level-item">
            <div class="card is-shady">
                <div class="card-content">
                    <div class="content">
                        <h4>{{ trans('app.expansible_card_title') }}</h4>
                        <p>{{ trans('app.expansible_card_description') }}</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="{{ route('about.partnering') }}" class="card-footer-item">{{ trans('app.expansible_card_action') }}</a>
                </footer>
            </div>
        </div>

        <div class="column is-4 level-item">
            <div class="card is-shady">
                <div class="card-content">
                    <div class="content">
                        <h4>{{ trans('app.open_source_card_title') }}</h4>
                        <p>{{ trans('app.open_source_card_description') }}</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="{{ route('license') }}" class="card-footer-item">License</a>
                    <a target="_blank" href="https://github.com/digitalbiblesociety/dbp" class="card-footer-item">Github</a>
                </footer>
            </div>
        </div>

        <div class="column is-4 level-item">
            <div class="card is-shady">
                <div class="card-content">
                    <div class="content">
                        <h4>{{ trans('app.open_api_card_title') }}</h4>
                        <p>{{ trans('app.open_api_card_description') }}</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="{{ route('docs.getting_started') }}" class="card-footer-item">{{ trans('app.open_api_action') }}</a>
                </footer>
            </div>
        </div>
    </div>
    </div>
</section>

@endsection