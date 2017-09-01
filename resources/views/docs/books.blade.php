@extends('layouts.app')

@section('head')
    <style>
        small {display: block}
    </style>
@endsection

@section('content')

    @include('layouts.banner', [
        'title'           => "Books",
        'title_class'     => '',
        'subtitle'        => '',
        'subtitle_class'  => '',
        'backgroundImage' => null,
        'noGradient' => true,
        'breadcrumbs' => [
            '/docs'    => trans('docs.title'),
            '/docs/v2' => trans('docs.version2'),
            '#'        => ''
        ],
        'tabs' => [
		    'v4_books' => trans('fields.v4_books'),
		   	'v2_books' => trans('fields.v2_books'),
		]
    ])

    <section role="tabpanel" aria-hidden="false" id="v4_books">
        <div class="medium-7 columns">
            <h1 class="text-center">{{ trans('docs.v4_books_index_title') }} <small>{{ trans('docs.v4_books_index_subtitle') }}</small></h1>
            <p>{{ trans('docs.v4_books_description') }}</p>
        </div>
        <div class="medium-5 columns">
            <pre><code> <?php echo fetchAPI(route('v4_api_books.index', ['key' => env('API_KEY'), 'v' => 4])); ?> </code></pre>
        </div>
    </section>

    <section role="tabpanel" aria-hidden="true" id="v2_books">

    </section>


@endsection