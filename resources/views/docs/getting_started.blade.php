@extends('layouts.app')

@section('head')

    <style>
        h1 {
            font-size: medium;
            text-align: center;
        }

        h2 {
            font-size: 16px
        }

        h3 {
            font-size: 26px;
            font-weight: 400;
            margin-top: 45px;
            margin-bottom: 15px;
            position: relative;
            text-transform: none;
            color: #666;
            text-indent: 25px;
        }
        h3:before {content: "#"}

        dl dt {
            text-indent: 35px;
        }

        section {
            width: 800px;
            margin: 0 auto;
        }

        section p {
            font-size: 14px;
            text-align: justify;
            line-height: 1.8;
        }

        section a {
            background: #f0f2f1;
            padding: 1px 5px;
            border-radius: 3px;
        }

        section code {
            font-family: "Operator Mono", "Fira Code", Consolas, Monaco, "Andale Mono", monospace;
        }

        a {
            color: #88bb44;
        }

    </style>
    <link rel="stylesheet"
          href="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/styles/default.min.css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/highlight.js/9.12.0/highlight.min.js"></script>
    <script>hljs.initHighlightingOnLoad();</script>
@endsection

@section('content')

    @include('docs.navigation')

    <h1>Digital Bible Platform: Koinos</h1>

    <section>
        <h3>Getting an API key</h3>
        <p>The first thing you'll need to do is obtain an API key. You can do this in moments by simply
            <a target="_blank" href="{{ route('login') }}">creating an account</a>. If you represent a biblical organization or if
            multiple members within your organization will also be using API keys please see the
            <a target="_blank" href="{{ route('dashboard_organization_roles.create') }}">organizations page on your dashboard</a>
            after you create an account.</p>

        <h3>Querying a Bible & a Fileset </h3>
        <p>The first route you may wish to query is the
            <a target="_blank" href="{{ route('swagger_docs_ui').'#/Version_4/v4_bible_all' }}"><code>/bibles</code></a> route.
            This will provide a complete list of bibles within the DBP.v4 from all providers. Once you have the return
            you can either query the <a target="_blank" href="{{ route('swagger_docs_ui').'#/Version_4/v4_bible_one' }}"><code>/bibles/{id}</code></a> route for additional metadata or query the fileset of your choice via
            the <a target="_blank" href="{{ route('swagger_docs_ui').'#/Version_4/v4_bible_filesets_show' }}"><code>/bibles/filesets/{id}</code></a> route.
            Depending on the the type of fileset you've queried you'll either receive the text itself in the return or file_paths to audio/html files.</p>
        <p>The Digital Bible Platform v4 is an open system, meaning any Bible organization may join as a supplier of
            content by creating an s3 bucket. Currently there are {{ \App\Models\Organization\Bucket::count() }}
            <a target="_blank" href="{{ route('swagger_docs_ui').'#/Version_4/v4_api_buckets' }}"><code>/buckets</code></a>
            available. The Id from these buckets can be used in various routes to filter the results to only return
            filesets from the bucket specified.</p>

        <h3>Querying a Language & Alphabet</h3>
        <p>
            <code><a href="{{ route('swagger_docs_ui').'' }}">/countries</a></code>
            <p>The countries route is the general list for</p>
            <code><a href="{{ route('swagger_docs_ui').'' }}">/countries/joshua-project/</a></code>
            <p>The countries route is the general list for</p>
            <code><a href="{{ route('swagger_docs_ui').'' }}">/countries/{id}</a></code>
            <p>The countries id route is the general list for</p>
        </p>

        <h3>Getting a Project Key and Users</h3>
        <p>In order to interact with the users portion of the API you first need to obtain a project_id.
           Your personal project_id should be kept secret as it's the distinguishing field to filter your users by.
           You can sign up for a project via the <a href="{{ route('swagger_docs_ui') }}"><code>/projects</code></a> route.
        </p>

    </section>

    <section>

        <h3>API Organization</h3>
        <p>All calls within version 4 of the API are separated into three general categories: Bibles, Wiki, and Community.</p>

        <h4>Bibles</h4>
        <p>The routes categorized under the Bibles tag are generally focused on querying information about bibles and audio or text content of those Bibles.</p>

        <h4>Wiki</h4>
        <p></p>

        <h4>Community</h4>
        <p></p>

    </section>


@endsection