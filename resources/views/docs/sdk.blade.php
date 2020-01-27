@extends('layouts.app')

@section('head')
    <style>

        .sdk-links a {
            height:150px;
            text-align: center;
            color:#222;
        }

        .panel-icon {
            font-size:30px;
        }


        .sdk-links img {
            width:50px;
            margin:10px auto;
            display: block;
        }

        .swift4 {
            color:#fe4216;
        }

        .swift3 {
            color:#444;
        }

    </style>
@endsection

@section('content')

    @include('layouts.partials.banner', ['title' => "SDKs"])

    <div class="container">
    <div class="row">

    </div>

        <div class="box">

            <div>
                <p>You can generate your own SDK using our swagger specifications located here: <span></span>. In order to do so you'll need to swagger-codegen.</p>
                <code><pre>swagger-codegen generate -i {{ route('swagger_docs_gen', ['version' => 'v4']) }} -l php -o /Sites/dbp/public/sdk/</pre></code>
                <p>Or use one of pre generated SDKs for commonly used programming languages.</p>
            </div>


            <div class="columns">
                <nav class="panel column">
                    <p class="panel-heading">Generated SDKs</p>
                    <a class="panel-block" href="https://github.com/digitalbiblesociety/dbp-sdks/tree/master/java"><svg class="panel-icon icon"><use xlink:href="/images/icons-programming.svg#java"></use></svg>Java</a>
                    <a class="panel-block" href="https://github.com/digitalbiblesociety/dbp-sdks/tree/master/php"><svg class="panel-icon icon"><use xlink:href="/images/icons-programming.svg#php"></use></svg>php</a>
                    <a class="panel-block" href="https://github.com/digitalbiblesociety/dbp-sdks/tree/master/swift3"><svg class="panel-icon icon swift3"><use xlink:href="/images/icons-programming.svg#swift"></use></svg> Swift 3</a>
                    <a class="panel-block" href="https://github.com/digitalbiblesociety/dbp-sdks/tree/master/swift4"><svg class="panel-icon icon swift4"><use xlink:href="/images/icons-programming.svg#swift"></use></svg> Swift 4</a>
                    <a class="panel-block" href="https://github.com/digitalbiblesociety/dbp-sdks/tree/master/typescript"><svg class="panel-icon icon swift4"><use xlink:href="/images/icons-programming.svg#typescript"></use></svg> typescript</a>
                </nav>
                <nav class="panel column">
                    <p class="panel-heading">Examples</p>
                    <a class="panel-block"><span class="panel-icon"></span>Coming Soon</a>
                </nav>
            </div>
            <div class="columns">
                <a href="https://github.com/digitalbiblesociety/dbp-sdks/releases" class="has-text-centered has-text-grey column is-size-6">Older Versions</a>
            </div>

        </div>

    </div>

@endsection
