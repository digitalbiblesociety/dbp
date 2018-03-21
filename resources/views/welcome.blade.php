@extends('layouts.app')

@section('head')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/androidstudio.min.css" rel="stylesheet" />
    <style>
        #stats {
            padding-bottom: 5px;
            height:80px;
            margin:0 auto 10px;
            border-bottom: thick solid #00b09b;
        }


        .stat {
            font-size:1rem;
        }
        .stat a {
            color:#666;
        }

        .stat:before {
            content: attr(data-stat);
            display: block;
            font-size:2rem
        }
        #banner {
            padding-bottom:70px;
            margin-bottom:70px;
            background: #222;
            color:#f8f8f8;
        }
        #banner p {
            text-align: justify;
            max-width:500px;
            margin:2rem auto;
        }

        #banner h1 {
            text-transform: uppercase;
            letter-spacing: 3px;
            padding: 70px;
            font-size:2rem;
        }
    </style>
@endsection

@section('content')

    <section id="banner">
        <h1 class="text-center">The Bible in your language on your Site</h1>
        <div class="medium-6 columns centered">
            <a class="button medium-4 columns" href="{{ route('swagger_v4') }}">v4 Documentation</a>
            <a class="button secondary medium-4 columns" href="{{ route('register') }}">Get Started</a>
            <a class="button medium-4 columns" href="{{ route('swagger_v2') }}">v2 Documentation</a>
        </div>
    </section>

    <section id="stats" class="text-center">
        <a class="small-6 medium-3 columns stat" href="/languages" data-stat="{{ $count['languages'] or 0 }}">{{ trans('docs.languages') }}</a>
        <a class="small-6 medium-3 columns stat" href="/countries" data-stat="{{ $count['countries'] or 0 }}">{{ trans('docs.countries') }}</a>
        <a class="small-6 medium-3 columns stat" href="/alphabets" data-stat="{{ $count['alphabets'] or 0 }}">{{ trans('docs.alphabets') }}</a>
        <a class="small-6 medium-3 columns stat" href="/bibles" data-stat="{{ $count['bibles'] or 0 }}">{{ trans('docs.bibles') }}</a>
    </section>

    <div class="row">
        <div class="medium-9 columns centered">
            <pre><code class="markup"><?php echo file_get_contents(base_path('CHANGELOG')); ?></code></pre>
        </div>
    </div>

@endsection

@section('footer')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/languages/markdown.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
    <script>
        $(document).ready(function() {
            $('pre code').each(function(i, block) {
                hljs.highlightBlock(block);
            });
        });
    </script>
@endsection