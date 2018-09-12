@extends('layouts.app')

@section('head')

    <style>
        .card {
            height: 100%!important;
        }

        .card .image {
            padding-top:10px;
            margin: 0 auto;
        }

        i {
            font-size:1.5rem;
        }
    </style>

@endsection

@section('content')
<section class="hero is-primary is-medium is-bold">
    <div class="hero-body">
        <div class="container has-text-centered">
            <h1 class="title">The Open Bible API</h1>
            <h2 class="subtitle">A collection of thousands of Bibles and resources brought to you by Faith Comes By Hearing<br> with the partnership of the Forum of Bible Agencies and Digital Bible Society.</h2>
            <div class="field is-grouped columns is-mobile is-centered">
                <p class="control"><a href="/docs/swagger/v4/" class="button is-link">Get Started</a></p>
                {{-- <p class="control"><a href="https://github.com/digitalbiblesociety/dbp" class="button">Github</a></p> --}}
            </div>
        </div>
    </div>
</section>

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