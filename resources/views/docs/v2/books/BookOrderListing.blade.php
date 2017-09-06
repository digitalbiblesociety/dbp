@extends('layouts.app')

@section('content')

    @include('layouts.banner', [
        'title'           => trans('docs.books_orderListing_title'),
        'title_class'     => '',
        'subtitle'        => '',
        'subtitle_class'  => '',
        'backgroundImage' => null,
        'noGradient' => true,
        'breadcrumbs' => [
            '/docs'    => trans('docs.title'),
            '/docs/v2' => trans('docs.version2'),
            '#'        => ''
        ]
    ])

    <div class="row">
    <aside class="medium-3 columns panel">
        <p class="text-center">An equivalent for this route exists in Version 4 of the API! We highly recommend you check it out. <a hef="{{ route('docs_books_BookOrderListing') }}">Version 4</a></p>
    </aside>
    <div class="medium-8 columns">
        <div class="collapsed">
            <h2></h2>
            <ul>
                <li class="required"><b>v:</b> Specifies the version of the API requested.</li>
                <li class="required"><b>key:</b> Your DBT API key provided upon registration.</li>
                <li><b>reply:</b> [json|jsonp|html|xml|yaml] Specifies the response type requested by the caller. Default is json.</li>
                <li><b>callback:</b> Specifies the name of the function returned when a JSONP reply is requested. (Requires reply = jsonp)</li>
                <li><b>echo:</b> [true|false] Not available in v. 1. If unused the results are as before. If set to \'true\', the results will be prepended with the array of calling arguments used. This does mean that the results will be one layer deeper as the results will be an array where the first element is the array of input arguments and the second element will be the results as normally returned.</li>
                <li><b>_method=put:</b> REQUIRED for PUT DBT methods - PUT is not properly supported. To effect DBT methods requiring PUT, use the GET HTTP method and &_method=put.</li>
            </ul>
        </div>
    </div>
    </div>

    <pre>
        <code>
            {{ file_get_contents(route('v2_library_bookOrder')) }}
        </code>
    </pre>


@endsection

