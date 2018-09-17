@extends('layouts.app')

@section('content')

    @include('layouts.partials.banner', [
        'title'     => trans('app.site_name'),
        'subtitle'  => trans('app.site_description'),
        'size'      => 'medium',
        'image'     => '/images/dbp_icon.svg',
        'actions'   => [
            '/docs/swagger/v4/' => trans('app.site_getting_started')
        ]
    ])

<section class="container">
    <div class="columns features level">

        <div class="column is-4 level-item">
            <div class="card is-shady">
                <div class="card-content">
                    <div class="content">
                        <h4>Expansible Architecture</h4>
                        <p>The OBA API joins together multiple organization's content via Amazon's S3 Buckets and it's easy to add your bucket to the list.</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="/api/buckets/" class="card-footer-item">Learn More</a>
                </footer>
            </div>
        </div>

        <div class="column is-4 level-item">
            <div class="card is-shady">
                <div class="card-content">
                    <div class="content">
                        <h4>Open Source</h4>
                        <p>Published with an MIT license, the code for the DBP is open to the community of bible developers to build plugins and extensions for.</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="/about/license" class="card-footer-item">License</a>
                    <a target="_blank" href="https://github.com/digitalbiblesociety/dbp" class="card-footer-item">Github</a>
                    <a href="/about/contributing" href="#" class="card-footer-item">Contributing</a>
                </footer>
            </div>
        </div>

        <div class="column is-4 level-item">
            <div class="card is-shady">
                <div class="card-content">
                    <div class="content">
                        <h4>Open Api 3.0</h4>
                        <p>Injest the api with any of our SDKs or any system compatible with the OAS 3.0 spec and be up and running in 5 minutes</p>
                    </div>
                </div>
                <footer class="card-footer">
                    <a href="/docs/" class="card-footer-item">Learn More</a>
                </footer>
            </div>
        </div>
    </div>
    </div>
</section>

@endsection